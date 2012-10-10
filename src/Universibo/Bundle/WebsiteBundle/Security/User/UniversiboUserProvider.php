<?php

namespace Universibo\Bundle\WebsiteBundle\Security\User;

use FOS\UserBundle\Model\UserManager;

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
     * @var UserManager
     */
    private $userManager;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository, UserManager $userManager)
    {
        $this->userRepository = $userRepository;
        $this->userManager = $userManager;
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
            // TODO move elsewhere
            $user->setLastLogin(new \DateTime());
            $this->userManager->updateUser($user);
            
            return $user;
        }

        if (!array_key_exists('isMemberOf', $claims) || $claims['isMemberOf'] === null) {
            return null;
        }

        switch ($claims['isMemberOf']) {
            case 'Studente':
                list($username, $dominio) = split('@', $email = $claims['eppn']);

                // actually this password will be never used
                $password = substr(sha1(rand(1,65536)), 0, rand(8,12));
                $user = new User();
                $user->setUsername($username);
                $user->setPlainPassword($password);
                $user->setEmail($email);
                $user->setShibUsername($email);
                $user->setEnabled(true);
                $user->setLegacyGroups(2);
                $user->setNotifications(0);
                $user->setLastLogin(new \DateTime());
                $user->addRole('ROLE_STUDENT');

                return $this->userManager->updateUser($user);
        }

        throw new UsernameNotFoundException('User not found');
    }
}
