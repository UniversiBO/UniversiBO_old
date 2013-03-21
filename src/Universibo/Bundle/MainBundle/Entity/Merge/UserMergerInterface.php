<?php
namespace Universibo\Bundle\MainBundle\Entity\Merge;

use LogicException;
use Universibo\Bundle\CoreBundle\Entity\Person;
use Universibo\Bundle\CoreBundle\Entity\User;

interface UserMergerInterface
{
    /**
     * Find users which belong to a persons
     *
     * @param Person  $person
     * @param boolean $includeLocked include locked users
     */
    public function getUsersFromPerson(Person $person, $includeLocked = false);

    /**
     * Merge one or more users to target
     * @param User  $target
     * @param array $others user to merge and lock
     */
    public function merge(User $target, array $others, Person $person = null);

    /**
     * Gets the resources owned by a user (files, etc)
     * @param  User  $user
     * @return array
     */
    public function getOwnedResources(User $user);

    /**
     * Gets the target person
     *
     * @param  array          $users
     * @return User|null
     * @throws LogicException if users belong to different people
     */
    public function getTargetPerson(array $users);
}
