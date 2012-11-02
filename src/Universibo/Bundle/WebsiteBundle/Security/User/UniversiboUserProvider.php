<?php

namespace Universibo\Bundle\WebsiteBundle\Security\User;

use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use FOS\UserBundle\Model\UserManager;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Universibo\Bundle\CoreBundle\Entity\Group;
use Universibo\Bundle\CoreBundle\Entity\GroupRepository;
use Universibo\Bundle\CoreBundle\Entity\Person;
use Universibo\Bundle\CoreBundle\Entity\PersonRepository;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\CoreBundle\Entity\UserRepository;
use Universibo\Bundle\LegacyBundle\Auth\LegacyRoles;
use Universibo\Bundle\ShibbolethBundle\Security\User\ShibbolethUserProviderInterface;

class UniversiboUserProvider implements ShibbolethUserProviderInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

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
     * @var GroupRepository
     */
    private $groupRepository;

    /**
     * MemberOf -> Group mapping
     *
     * @var array
     */
    private static $groupMap = array(
        'default' => array(
            'legacy' => LegacyRoles::PERSONALE,
            'locked' => true
        ),
        'Docente' => array(
            'legacy' => LegacyRoles::DOCENTE,
            'locked' => true
        ),
        'Studente' => array(
            'legacy' => LegacyRoles::STUDENTE,
            'locked' => false
        )
    );

    /**
     * Class constructor
     *
     * @param UserRepository   $userRepository
     * @param UserManager      $userManager
     * @param PersonRepository $personRepository
     * @param LoggerInterface  $logger
     */
    public function __construct(EntityManager $entityManager, UserRepository $userRepository, UserManager $userManager, PersonRepository $personRepository, GroupRepository $groupRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->userManager = $userManager;
        $this->personRepository = $personRepository;
        $this->groupRepository = $groupRepository;
    }

    /**
     *
     * @param  array                   $claims
     * @return User
     * @throws AuthenticationException
     * @throws AuthenticationException
     */
    public function loadUserByClaims(array $claims)
    {
        $this->ensureClaimsAvailable($claims);
        $this->entityManager->beginTransaction();

        try {
            $person = $this->findOrCreatePerson($claims['idAnagraficaUnica'], $claims['givenName'], $claims['sn']);

            $user = $this->loadUser($person);

            if ($user === null) {
                $user = $this->createUser($person, $claims['eppn'], $claims['isMemberOf']);
            } else {
                $user = $this->updateGroupAndEmail($user, $claims['eppn'], $claims['isMemberOf']);
            }
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw $e;
        }

        $this->entityManager->commit();
    }

    /**
     * Throws an exception if some claim is missing
     *
     * @param  array                   $claims
     * @throws AuthenticationException
     */
    private function ensureClaimsAvailable(array $claims)
    {
        // eppn              : unibo.it e-mail address
        // givenName         : first name
        // idAnagraficaUnica : person unique id
        // isMemberOf        : group: Docente, Studente, PersonaleTA or empty
        // sn                : surname
        $requiredKeys = array(
            'eppn',
            'givenName',
            'idAnagraficaUnica',
            'isMemberOf',
            'sn'
        );

        $missingKeys = array_diff($requiredKeys, array_keys($claims));
        if (count($missingKeys) > 0) {
            throw new AuthenticationException('Missing claims: ' . implode(', ', $missingKeys));
        }
    }

    /**
     * Gets a user for person
     *
     * @param  Person                  $person
     * @return User|null
     * @throws AuthenticationException if multiple users
     */
    private function loadUser(Person $person)
    {
        try {
            return $this->userRepository->findOneNotLocked($person);
        } catch (NonUniqueResultException $e) {
            throw new AuthenticationException('Person with multiple users');
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * Finds or creates a person, updating givenName and surname
     *
     * @param  integer $uniboId
     * @param  string  $givenName
     * @param  string  $surname
     * @return Person
     */
    private function findOrCreatePerson($uniboId, $givenName, $surname)
    {
        $person = $this->personRepository->findOneByUniboId($uniboId);

        if (!$person instanceof Person) {
            $person = new Person();
            $person->setUniboId($uniboId);
        }

        $person->setGivenName($givenName);
        $person->setSurname($surname);

        return $this->personRepository->save($person);
    }

    private function createUser(Person $person, $eppn, $isMemberOf)
    {
        $user = new User();
        $user->setUsername($this->getAvailableUsername($eppn));
        $user->setEmail($eppn);
        $user->setPerson($person);
        $user->setPlainPassword(sha1(uniqid()));
        $user->setLastLogin(new DateTime());

        $this->setUserGroup($user, $isMemberOf);
        $this->userManager->updateUser($user);

        return $user;
    }

    /**
     * Gets an available username
     *
     * @param  string $eppn
     * @return string
     */
    private function getAvailableUsername($eppn)
    {
        list($username, $dominio) = split('@', $eppn);

        $i = 1;
        $okUsername = $username;

        while ($this->userManager->findUserByUsername($okUsername) instanceof User) {
            $okUsername = $username . ++$i;
        }

        return $okUsername;
    }

    /**
     * Updates group and email (old group is not removed)
     *
     * @param  User   $user
     * @param  string $eppn
     * @param  string $isMemberOf
     * @return User
     */
    private function updateGroupAndEmail(User $user, $eppn, $isMemberOf)
    {
        $this->setUserGroup($user, $isMemberOf, true);
        $user->setEmail($eppn);

        return $this->userRepository->save($user);
    }

    /**
     * Sets users to group
     *
     * @param User   $user
     * @param string $isMemberOf
     */
    private function setUserGroup(User $user, $isMemberOf, $usernameLocked = false)
    {
        $key = array_key_exists($isMemberOf, self::$groupMap) ? $isMemberOf : 'default';

        if ($user->getLegacyGroups() < 1) {
            $user->setLegacyGroups(self::$groupMap[$key]['legacy']);
        }

        $user->setUsernameLocked($usernameLocked || self::$groupMap[$key]['locked']);

        $this->addFosGroup($user, $isMemberOf);
    }

    /**
     * Adds a FOSUserBundle group to user
     *
     * @param User   $user
     * @param string $isMemberOf
     */
    private function addFosGroup(User $user, $isMemberOf)
    {
        $groupName = 'MemberOf' . (!empty($isMemberOf) ? ucfirst($isMemberOf) : 'Empty');
        $group = $this->findOrCreateGroup($groupName);

        $user->addGroup($group);
    }

    /**
     * Finds or creates a group
     *
     * @param  string $groupName
     * @return Group
     */
    private function findOrCreateGroup($groupName)
    {
        $group = $this->groupRepository->findOneByName($groupName);

        if ($group === null) {
            $group = new Group($groupName);
            $this->entityManager->persist($group);
            $this->entityManager->flush($group);
        }

        return $group;
    }

}
