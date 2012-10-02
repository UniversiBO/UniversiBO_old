<?php

namespace Universibo\Bundle\LegacyBundle\Service;

/**
 * Translates role names
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
use Universibo\Bundle\LegacyBundle\Auth\LegacyRoles;

use Universibo\Bundle\CoreBundle\Entity\User;

class RoleNameTranslator
{
    private static $translation = array (
            'ROLE_ADMIN' => 'Admin',
            'ROLE_STUDENT' => 'Studente',
            'ROLE_PROFESSOR' => 'Docente',
            'ROLE_STAFF' => 'Personale non docente',
            'ROLE_TUTOR' => 'Tutor',
            'ROLE_COLLABORATOR' => 'Collaboratore'
    );

    /**
     * @param  array  $roles
     * @return strung
     */
    public function translate(array $roles)
    {
        $roles = array_diff($roles, array('ROLE_USER'));
        $role = array_pop($roles);

        return self::$translation[$role];
    }

    /**
     * @param  boolean $singular
     * @param  mixed   $user
     * @return string
     */
    public function getUserPublicGroupName($user, $singular = true)
    {
        $map = LegacyRoles::$map[$singular ? 'singular' : 'plural'];
        $groups = $user instanceof User ? $user->getLegacyGroups() : LegacyRoles::OSPITE;

        return $map[$groups];
    }
}
