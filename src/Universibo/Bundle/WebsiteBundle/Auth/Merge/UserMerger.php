<?php

namespace Universibo\Bundle\WebsiteBundle\Auth\Merge;

use Universibo\Bundle\CoreBundle\Entity\Person;
use Universibo\Bundle\CoreBundle\Entity\User;

class UserMerger implements UserMergerInterface
{
    public function getOwnedResources(User $user)
    {
    }

    public function getUsersFromPerson(Person $person, $includeLocked = false)
    {
    }

    public function merge(User $target, array $others)
    {
    }
}
