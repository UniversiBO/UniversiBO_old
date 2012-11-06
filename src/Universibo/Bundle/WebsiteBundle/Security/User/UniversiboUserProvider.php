<?php

namespace Universibo\Bundle\WebsiteBundle\Security\User;

use Symfony\Component\Security\Core\User\User;
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
     * @param PersonRepository $personRepository
     * @param UserRepository   $userRepository
     */
    public function __construct(PersonRepository $personRepository,
            UserRepository $userRepository)
    {
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

        $person = $this->personRepository->findOneByUniboId($uniboId);

        return $this->userRepository->findOneNotLocked($person);
    }
}
