<?php

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @copyright (c) 2002-2012, Associazione UniversiBO
 * @license GPLv2
 */

namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\Framework\Error;
use Smarty;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Auth\LegacyRoles;

/**
 * Common base class for File(Add|Edit|Delete|...)
 */
abstract class FileCommon extends UniversiboCommand
{
    /**
     * Validates file download permissions, throwing legacy error if not valid
     *
     * @param integer $permissions
     * @param User    $user
     * @param Smarty  $template
     */
    protected function validateDownloadPermissions($permissions, User $user,
            Smarty $template)
    {
        if (!preg_match('/^([0-9]{1,3})$/', $permissions)) {
            Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                'msg' => 'Il formato del campo minuto di inserimento non e` valido',
                'file' => __FILE__, 'line' => __LINE__,
                'log' => false,
                'template_engine' => $template));
        } elseif (!$this->isFilePermissionsValid($permissions, $user->hasRole('ROLE_ADMIN'))) {
            Error::throwError(_ERROR_NOTICE, array('id_utente' => $user->getId(),
                'msg' => 'Il valore dei diritti di download non e` ammissibile',
                'file' => __FILE__, 'line' => __LINE__,
                'log' => false,
                'template_engine' => $template));
        }
    }

    /**
     * Validates file download permission values
     *
     * @param integer $permissions
     * @param boolean $isAdmin
     */
    protected function isFilePermissionsValid($permissions, $isAdmin = false)
    {
        if ($permissions < LegacyRoles::NONE || $permissions > LegacyRoles::ALL) {
            return false;
        }

        return $isAdmin || in_array($permissions, array(
            LegacyRoles::ALL,
            LegacyRoles::ALL & ~LegacyRoles::OSPITE)
        );
    }
}
