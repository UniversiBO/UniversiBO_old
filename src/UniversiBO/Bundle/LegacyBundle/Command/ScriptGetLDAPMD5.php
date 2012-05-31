<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;

use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * ChangePassword is an extension of UniversiboCommand class.
 *
 * Si occupa della modifica della password di un utente
 *
 * @todo obsoleto
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ScriptGetLDAPMD5 extends UniversiboCommand
{
    public function execute()
    {
        $fc = $this->getFrontController();
        $template = $fc->getTemplateEngine();
        $db = $fc->getDbConnection('main');
        $user = $this->getSessionUser();
        $filePath = $fc->getAppSetting('filesPath');

        $db = $fc->getDbConnection('main');

        $query = 'SELECT username, password FROM utente WHERE groups IN (4,64)';
        if ( array_key_exists('user',$_GET))
            if( User::usernameExists($_GET['user']))
            $query .= ' AND username = ' . $db->quote($_GET['user']);
        else {echo 'Username non esistente'; die;
        }
        echo $query . "\n";
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        $rows = $res->numRows();
        if( $rows == 0) die('niente password :-p');

        while ( $res->fetchInto($row) ) {
            echo $row[0].' {MD5}' . base64_encode(pack("H*", $row[1]));
            echo '<br />';
        }
        $res->free();
    }
}
