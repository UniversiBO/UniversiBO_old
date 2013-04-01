<?php

namespace Universibo\Bundle\MainBundle\Format;

use Universibo\Bundle\MainBundle\Entity\User;

class GivenNameSurnameUserFormatter implements UserFormatterInterface
{
    /**
     * Returns the format name
     *
     * @return string
     */
    public function getName()
    {
        return 'given_name_surname';
    }

    /**
     * Converts a User to string
     *
     * @param  User   $user
     * @return string
     */
    public function format(User $user)
    {
        $person = $user->getPerson();

        return $person->getGivenName() . ' ' . $person->getSurname();
    }
}
