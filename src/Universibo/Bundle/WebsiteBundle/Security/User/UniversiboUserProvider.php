<?php

namespace Universibo\Bundle\WebsiteBundle\Security\User;

use Universibo\Bundle\CoreBundle\Entity\Person;

use Universibo\Bundle\CoreBundle\Entity\PersonRepository;

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
     * @var PersonRepository
     */
    private $personRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository, UserManager $userManager, PersonRepository $personRepository)
    {
        $this->userRepository = $userRepository;
        $this->userManager = $userManager;
        $this->personRepository = $personRepository;
    }

    /**
     * (non-PHPdoc)
     * @see Universibo\Bundle\ShibbolethBundle\Security\User.ShibbolethUserProviderInterface::loadUserByClaims()
     */
    public function loadUserByClaims(array $claims)
    {
        if (!array_key_exists('idAnagraficaUnica', $claims) || $claims['idAnagraficaUnica'] === null) {
            return null;
        }

        $uniboId = $claims['idAnagraficaUnica'];
        $person = $this->personRepository->findOneByUniboId($uniboId);

        if (!$person instanceof Person) {
            $person = new Person();
            $person->setUniboId($uniboId);
            $person->setGivenName($claims['givenName']);
            $person->setSurname($claims['surname']);

            $this->personRepository->save($person);
        }

        $user = $this->userRepository->findOneByShibUsername($claims['eppn']);

        if ($user instanceof User) {
            // TODO move elsewhere
            $user->setLastLogin(new \DateTime());
            $user->setPerson($person);
            $this->userManager->updateUser($user);

            return $user;
        }

        if (!array_key_exists('isMemberOf', $claims) || $claims['isMemberOf'] === null) {
            return null;
        }

        $allowedMemberOf = array('Studente');
        if (!in_array($memberOf = $claims['isMemberOf'], $allowedMemberOf)) {
            throw new UsernameNotFoundException('User not found');
        }

        list($username, $dominio) = split('@', $email = $claims['eppn']);

        $user = new User();
        $user->setUsername($username);
        $user->setPlainPassword(substr(sha1(rand(1,65536)), 0, rand(8,12)));
        $user->setEmail($email);
        $user->setShibUsername($email);
        $user->setEnabled(true);
        $user->setLastLogin(new \DateTime());
        $user->setMemberOf($memberOf);
        $user->setNotifications(0);

        switch ($memberOf) {
            case 'Studente':
                // actually this password will be never used
                $user->setLegacyGroups(2);
                $user->addRole('ROLE_STUDENT');
        }

        return $this->userManager->updateUser($user);
    }
}
