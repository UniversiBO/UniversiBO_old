<?php

namespace Universibo\Bundle\WebsiteBundle\Security\User;

use DateTime;
use Doctrine\DBAL\DBALException;
use FOS\UserBundle\Model\UserManager;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Universibo\Bundle\CoreBundle\Entity\Person;
use Universibo\Bundle\CoreBundle\Entity\PersonRepository;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\CoreBundle\Entity\UserRepository;
use Universibo\Bundle\LegacyBundle\Auth\LegacyRoles;
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
     * @var array
     */
    private $allowedMemberOf = array();

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository, UserManager $userManager, PersonRepository $personRepository)
    {
        $this->userRepository = $userRepository;
        $this->userManager = $userManager;
        $this->personRepository = $personRepository;

        $this->allowedMemberOf['PersonaleTA'] = function (User $user) {
            $user->setLegacyGroups(LegacyRoles::PERSONALE);
            $user->addRole('ROLE_STAFF');

            return $user;
        };

        $this->allowedMemberOf['Docente'] = function (User $user) {
            $user->setLegacyGroups(LegacyRoles::DOCENTE);
            $user->addRole('ROLE_PROFESSOR');

            return $user;
        };

        $this->allowedMemberOf['Studente'] = function (User $user) {
            $user->setLegacyGroups(LegacyRoles::STUDENTE);
            $user->addRole('ROLE_STUDENT');
            $user->setUsernameLocked(false);

            return $user;
        };
    }

    /**
     * (non-PHPdoc)
     * @see Universibo\Bundle\ShibbolethBundle\Security\User.ShibbolethUserProviderInterface::loadUserByClaims()
     */
    public function loadUserByClaims(array $claims)
    {
        $requiredKeys = array(
                'eppn',
                'givenName',
                'idAnagraficaUnica',
                'isMemberOf',
                'sn'
        );

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $claims) || $claims[$key] === null) {
                return null;
            }
        }

        $uniboId = $claims['idAnagraficaUnica'];
        $person = $this->ensurePerson($uniboId, $claims['givenName'], $claims['sn']);

        try {
            return $this->ensureUser($claims['eppn'], $claims['isMemberOf'], $person);
        } catch (DBALException $e) {
            throw new AuthenticationException('Ambiguous credentials');
        }
    }

    private function ensurePerson($uniboId, $givenName, $surname)
    {
        $person = $this->personRepository->findOneByUniboId($uniboId);

        if (!$person instanceof Person) {
            $person = new Person();
            $person->setUniboId($uniboId);
        }

        $person->setGivenName($givenName);
        $person->setSurname($surname);
        $this->personRepository->save($person);

        return $person;
    }

    private function ensureUser($eppn, $memberOf, Person $person)
    {
        $user = $this->userRepository->findOneByShibUsername($eppn);

        if (!$user instanceof User) {
            if (!array_key_exists($memberOf, $this->allowedMemberOf)) {
                throw new UsernameNotFoundException('Cannot map user');
            }

            if ($this->userRepository->findOneByEmail($eppn) instanceof User) {
                throw new AuthenticationException('Email exists');
            }

            $user = $this->allowedMemberOf[$memberOf](new User(), $eppn);
            $user->setUsername($this->getUsername($eppn));
            $user->setNotifications(0);
            $user->setShibUsername($eppn);
            $user->setEmail($eppn);
            $user->setEnabled(true);
        }

        $user->setPerson($person);
        $user->setLastLogin(new DateTime());
        $user->setMemberOf($memberOf);
        $user->setPlainPassword(substr(sha1(rand(1,65536)), 0, rand(8,12)));

        $this->userManager->updateUser($user);

        if ($this->userRepository->countByPerson($person) > 0) {
            throw new AuthenticationException('Person with multiple users');
        }

        return $user;
    }

    private function getUsername($eppn)
    {
        list($username, $dominio) = split('@', $eppn);

        $i = 1;
        $okUsername = $username;

        while ($this->userManager->findUserByUsername($okUsername) instanceof User) {
            $okUsername = $username . ++$i;
        }

        return $okUsername;
    }
}
