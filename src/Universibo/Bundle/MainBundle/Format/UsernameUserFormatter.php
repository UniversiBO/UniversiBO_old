<?php

namespace Universibo\Bundle\MainBundle\Format;

use Universibo\Bundle\MainBundle\Entity\User;

class UsernameUserFormatter implements UserFormatterInterface
{
    /**
     * Returns the format name
     *
     * @return string
     */
    public function getName()
    {
        return 'username';
    }

    /**
     * Converts a User to string according to $mode
     *
     * @param  User   $user
     * @return string
     */
    public function format(User $user)
    {
        return $user->getUsername();
    }
}
