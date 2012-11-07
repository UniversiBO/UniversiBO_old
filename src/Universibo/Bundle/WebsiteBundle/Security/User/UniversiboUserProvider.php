<?php

namespace Universibo\Bundle\WebsiteBundle\Security\User;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\User;
use Universibo\Bundle\CoreBundle\Entity\Person;
use Universibo\Bundle\CoreBundle\Entity\PersonRepository;
use Universibo\Bundle\CoreBundle\Entity\UserRepository;
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
     * Class constructor
     *
     * @param ObjectManager    $objectManager
     * @param PersonRepository $personRepository
     * @param UserRepository   $userRepository
     */
    public function __construct(ObjectManager $objectManager,
            PersonRepository $personRepository,
            UserRepository $userRepository)
    {
        $this->objectManager = $objectManager;
        $this->personRepository = $personRepository;
        $this->userRepository = $userRepository;
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

        $person = $this->findOrCreatePerson($uniboId, $claims['givenName'], $claims['sn']);

        return $this->userRepository->findOneNotLocked($person);
    }

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
}
