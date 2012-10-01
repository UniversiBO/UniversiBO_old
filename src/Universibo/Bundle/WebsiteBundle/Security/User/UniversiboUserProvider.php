<?php

namespace Universibo\Bundle\WebsiteBundle\Security\User;
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
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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

        $user = $this->userRepository->findOneByUpn($claims['eppn']);

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
                $user->setUsername($username);
                $user->setEmail($claims['eppn']);
                $user->setLegacyGroups(2);
                $user->addRole('ROLE_STUDENT');

                return $this->userRepository->save($user);
        }

        throw new UsernameNotFoundException('User not found');
    }
}
