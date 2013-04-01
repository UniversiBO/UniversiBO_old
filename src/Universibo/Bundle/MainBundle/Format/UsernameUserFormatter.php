<?php

namespace Universibo\Bundle\MainBundle\Format;

use Universibo\Bundle\CoreBundle\Entity\User;

class UsernameUserFormatter implements UserFormatterInterface
{
    /**
     * Returns true if a mode is supported, false otherwise
     *
     * @param  boolean $mode
     * @return boolean
     */
    public function supports($mode)
    {
        return $mode === 'username';
    }

    /**
     * Converts a User to string according to $mode
     *
     * @param  User   $user
     * @param  string $mode
     * @return string
     */
    public function format(User $user, $mode)
    {
        return $user->getUsername();
    }
}
