<?php

namespace Universibo\Bundle\MainBundle\Security\User;

use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Universibo\Bundle\MainBundle\Entity\Person;
use Universibo\Bundle\MainBundle\Entity\PersonRepository;
use Universibo\Bundle\MainBundle\Entity\UniboGroupRepository;
use Universibo\Bundle\MainBundle\Entity\User;
use Universibo\Bundle\MainBundle\Entity\UserRepository;
use Universibo\Bundle\LegacyBundle\App\Constants;
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
     * Logger
     *
     * @var LoggerInterface
     */
    private $logger;

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
     * @param LoggerInterface      $logger
     */
    public function __construct(ObjectManager $objectManager,
            PersonRepository $personRepository, UserRepository $userRepository,
            UserManagerInterface $userManager, UniboGroupRepository $uniboGroupRepository,
            LoggerInterface $logger)
    {
        $this->objectManager = $objectManager;
        $this->personRepository = $personRepository;
        $this->userRepository = $userRepository;
        $this->userManager = $userManager;
        $this->uniboGroupRepository = $uniboGroupRepository;
        $this->logger = $logger;

        $this->memberOfHandlers['Docente'] = function($user) {
            $user->setLegacyGroups(LegacyRoles::DOCENTE);
        };

        $this->memberOfHandlers['Studente'] = function($user) {
            $user->setLegacyGroups(LegacyRoles::STUDENTE);
        };

        $this->memberOfHandlers['Laureato'] = $this->memberOfHandlers['Studente'];
        $this->memberOfHandlers['Preiscritto'] = $this->memberOfHandlers['Studente'];

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
        $this->logMissingClaims($claims);

        $uniboId = $claims['idAnagraficaUnica'];
        $eppn = $claims['eppn'];

        if (empty($uniboId)) {
            throw new AuthenticationException('No idAnagraficaUnica given');
        }

        if (empty($eppn)) {
            throw new AuthenticationException('No EPPN given');
        }

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

        if (empty($memberOf)) {
            $memberOf = 'Nessuno';
        }

        if (preg_match('/;/', $memberOf)) {
            $multiMemberOf = explode(';', $memberOf);
            $memberOf = $multiMemberOf[0];
        } else {
            $multiMemberOf = array($memberOf);
        }

        if ($user === null) {
            $user = $this->userManager->createUser();
            $user->setUsernameLocked(false);
            $user->setEnabled(true);
            $user->setUsername($this->getAvailableUsername($eppn));
            $user->setEmail($eppn);
            $user->setPhone('');
            $user->setNotifications(Constants::NOTIFICA_ALL);
            // x will never match any hash, as wanted
            $user->setPassword('x');

            if (preg_match('/@studio.unibo.it$/', $eppn)) {
                $key = 'Studente';
            } else {
                $key = array_key_exists($memberOf, $this->memberOfHandlers) ? $memberOf : 'default';
            }

            $this->memberOfHandlers[$key]($user);
        }

        foreach ($multiMemberOf as $groupName) {
            $this->ensureGroup($user, $groupName);
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

    /**
     * Gets the available username, by adding a number at the end if needed
     *
     * @param  string $eppn email address (UPN)
     * @return string
     */
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

    /**
     * Ensure that a user has a Unibo group
     *
     * @param User   $user
     * @param string $groupName
     */
    private function ensureGroup(User $user, $groupName)
    {
        $found = false;
        foreach ($user->getUniboGroups() as $group) {
            if ($group->getName() === $groupName) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $user->getUniboGroups()->add($this->uniboGroupRepository->findOrCreate($groupName));
        }
    }

    /**
     * Checks the claims and detects empty values, by logging messages
     *
     * @param array $claims
     */
    private function logMissingClaims(array $claims)
    {
        $existingClaims = array();
        $missingClaims = array();

        foreach ($claims as $key => $claim) {
            if (empty($claim)) {
                $missingClaims[] = $key;
            } else {
                $existingClaims[$key] = $claim;
            }
        }

        if (count($missingClaims) > 0) {
            $msg = 'User has claims '. json_encode($existingClaims);
            $msg .= ' missing: ' . implode(', ', $missingClaims);
            $this->logger->warn($msg);
        }
    }
}
