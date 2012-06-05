<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use \DB;
use \Error;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * ChangePassword is an extension of UniversiboCommand class.
 *
 * Si occupa della modifica della password di un utente
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ScriptUpdateFileHash extends UniversiboCommand
{
    public function execute()
    {
        $fc = $this->getFrontController();
        $template = $fc->getTemplateEngine();
        $db = $fc->getDbConnection('main');
        $user = $this->getSessionUser();
        $filePath = $fc->getAppSetting('filesPath');

        //if(!$user->isAdmin())
        //	Error::throwError(_ERROR_DEFAULT,array('msg'=>'La modifica della password non pu? essere eseguita da utenti con livello ospite.'."\n".'La sessione potrebbe essere scaduta, eseguire il login','file'=>__FILE__,'line'=>__LINE__));

        $res = $db->query('SELECT id_file, nome_file FROM file ORDER BY 1');

        while ( $res->fetchInto($row) ) {
            $nome_file = $filePath.$row[0].'_'.$row[1];
            if (file_exists($nome_file)) {
                $query = 'UPDATE file SET hash_file=\''.md5_file($nome_file).'\' WHERE id_file = '.$row[0];
                $res1 = $db->query($query);
                if (DB::isError($res1))
                    Error::throwError(_ERROR_CRITICAL,array('id_utente' => $user->getIdUser(), 'msg'=>DB::errorMessage($res1),'file'=>__FILE__,'line'=>__LINE__));
            } else {
                echo $row[0].'_'.$row[1]."\n";
                $query = 'UPDATE file SET hash_file=\'\' WHERE id_file = '.$row[0];
                $res1 = $db->query($query);
                if (DB::isError($res1))
                    Error::throwError(_ERROR_CRITICAL,array('id_utente' => $user->getIdUser(), 'msg'=>DB::errorMessage($res1),'file'=>__FILE__,'line'=>__LINE__));
            }
        }

        $res->free();

    }
}
