<?php

namespace Universibo\Bundle\WebsiteBundle\Security\User;

use FOS\UserBundle\Util\UserManipulator;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

use Universibo\Bundle\CoreBundle\Entity\User;

use Universibo\Bundle\CoreBundle\Entity\UserRepository;

use Universibo\Bundle\ShibbolethBundle\Security\User\ShibbolethUserProviderInterface;

class UniversiboUserProvider implements ShibbolethUserProviderInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserManipulator
     */
    private $manipulator;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository, UserManipulator $manipulator)
    {
        $this->userRepository = $userRepository;
        $this->manipulator = $manipulator;
    }

    /**
     * (non-PHPdoc)
     * @see Universibo\Bundle\ShibbolethBundle\Security\User.ShibbolethUserProviderInterface::loadUserByClaims()
     */
    public function loadUserByClaims(array $claims)
    {
        if (!array_key_exists('eppn', $claims) || $claims['eppn'] === null) {
            return null;
        }

        $user = $this->userRepository->findOneByShibUsername($claims['eppn']);

        if ($user instanceof User) {
            return $user;
        }

        if (!array_key_exists('isMemberOf', $claims) || $claims['isMemberOf'] === null) {
            return null;
        }

        switch ($claims['isMemberOf']) {
            case 'Studente':
                $user = new User();
                list($username, $dominio) = split('@', $claims['eppn']);

                // actually this password will be never used
                $password = substr(sha1(rand(1,65536)), 0, rand(8,12));
                $user = $this->manipulator->create($username, $password, $claims['eppn'], true, false);
                $user->setLegacyGroups(2);
                $user->setNotifications(0);
                $user->addRole('ROLE_STUDENT');

                return $this->userRepository->save($user);
        }

        throw new UsernameNotFoundException('User not found');
    }
}
