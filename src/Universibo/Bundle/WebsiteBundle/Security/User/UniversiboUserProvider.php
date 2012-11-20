<?php

namespace Universibo\Bundle\WebsiteBundle\Security\User;

use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Universibo\Bundle\CoreBundle\Entity\Person;
use Universibo\Bundle\CoreBundle\Entity\PersonRepository;
use Universibo\Bundle\CoreBundle\Entity\UniboGroupRepository;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\CoreBundle\Entity\UserRepository;
use Universibo\Bundle\LegacyBundle\Auth\LegacyRoles;
use Universibo\Bundle\ShibbolethBundle\Security\User\ShibbolethUserProviderInterface;

/**
 * UniversiBO user provider, converts claims to user
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class UniversiboUserProvider implements ShibbolethUserProviderInterface
{
    /**
     * Object manager
     *
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * Person repository
     *
     * @var PersonRepository
     */
    private $personRepository;

    /**
     * User repository
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * User manager
     *
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * Unibo Group repository
     *
     * @var UniboGroupRepository
     */
    private $uniboGroupRepository;

    /**
     * MemberOf handlers
     *
     * @var array
     */
    private $memberOfHandlers = array();

    /**
     * Class constructor
     *
     * @param ObjectManager        $objectManager
     * @param PersonRepository     $personRepository
     * @param UserRepository       $userRepository
     * @param UserManagerInterface $userManager
     * @param UniboGroupRepository $uniboGroupRepository
     */
    public function __construct(ObjectManager $objectManager,
            PersonRepository $personRepository, UserRepository $userRepository,
            UserManagerInterface $userManager, UniboGroupRepository $uniboGroupRepository)
    {
        $this->objectManager = $objectManager;
        $this->personRepository = $personRepository;
        $this->userRepository = $userRepository;
        $this->userManager = $userManager;
        $this->uniboGroupRepository = $uniboGroupRepository;

        $this->memberOfHandlers['Docente'] = function($user) {
            $user->setLegacyGroups(LegacyRoles::DOCENTE);
        };

        $this->memberOfHandlers['Studente'] = function($user) {
            $user->setLegacyGroups(LegacyRoles::STUDENTE);
            $user->setUsernameLocked(false);
        };

        $this->memberOfHandlers['default'] = function($user) {
            $user->setLegacyGroups(LegacyRoles::PERSONALE);
        };
    }

    /**
     * Loads the user given claims
     *
     * @param  array     $claims
     * @return User|null
     */
    public function loadUserByClaims(array $claims)
    {
        $uniboId = $claims['idAnagraficaUnica'];
        $eppn = $claims['eppn'];

        $person = $this->findOrCreatePerson($uniboId, $claims['givenName'], $claims['sn']);

        $user = $this->findUserByPerson($person);
        if ($user === null) {
            $user = $this->userManager->findUserByEmail($eppn);
        } elseif ($user->getEmail() !== $eppn) {
            if ($this->userManager->findUserByEmail($eppn) !== null) {
                throw new AuthenticationException('Person with multiple accounts');
            }

            $user->setEmail($eppn);
        }

        $memberOf = $claims['isMemberOf'];

        // TODO make a test case for this
        if (empty($memberOf)) {
            $memberOf = 'Nessuno';
        }

        if ($user === null) {
            $user = $this->userManager->createUser();
            $user->setEnabled(true);
            $user->setUsername($this->getAvailableUsername($eppn));
            $user->setEmail($eppn);
            $user->setPhone('');
            // x will never match any hash, as wanted
            $user->setPassword('x');

            $key = array_key_exists($memberOf, $this->memberOfHandlers) ? $memberOf : 'default';
            $this->memberOfHandlers[$key]($user);
        }

        $found = false;
        foreach ($user->getUniboGroups() as $group) {
            if ($group->getName() === $memberOf) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $user->getUniboGroups()->add($this->uniboGroupRepository->findOrCreate($memberOf));
        }

        $user->setPerson($person);
        $user->setLastLogin(new DateTime());
        $this->userManager->updateUser($user);

        if ($user->isLocked() || !$user->isEnabled()) {
            throw new AuthenticationException('User is locked or not enabled');
        }

        return $user;
    }

    /**
     * Finds a person or creates it
     *
     * @param  int    $uniboId
     * @param  string $givenName
     * @param  string $surname
     * @return Person
     */
    private function findOrCreatePerson($uniboId, $givenName, $surname)
    {
        $person = $this->personRepository->findOneByUniboId($uniboId);

        if ($person === null) {
            $person = new Person();
            $person->setUniboId($uniboId);
        }

        $person->setGivenName($givenName);
        $person->setSurname($surname);

        return $this->objectManager->merge($person);
    }

    /**
     * Finds a user by person
     *
     * @param  Person    $person
     * @return User|null
     */
    private function findUserByPerson(Person $person)
    {
        try {
            return $this->userRepository->findOneAllowedToLogin($person);
        } catch (NoResultException $e) {
            return null;
        } catch (NonUniqueResultException $e) {
            throw new AuthenticationException('Non unique result');
        }
    }

    private function getAvailableUsername($eppn)
    {
        list($username, $domain) = explode('@', $eppn);

        // TODO fake implementation
        $targetUsername = $username;
        $i = 1;

        while ($this->userManager->findUserByUsername($targetUsername) !== null) {
            $targetUsername = $username . $i++;
        }

        return $targetUsername;
    }
}
