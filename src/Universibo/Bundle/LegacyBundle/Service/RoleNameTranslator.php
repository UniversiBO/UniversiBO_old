<?php

namespace Universibo\Bundle\LegacyBundle\Service;

/**
 * Translates role names
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
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
}
