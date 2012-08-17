<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use \DB;
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
        $db = $this->getContainer()->get('doctrine.dbal.default_connection');
        $user = $this->getSessionUser();
        $filePath = $fc->getAppSetting('filesPath');

        $res = $db->executeQuery('SELECT id_file, nome_file FROM file ORDER BY 1');

        $query = 'UPDATE file SET hash_file = ? WHERE id_file = ?';

        while ( false !== ($row = $res->fetch()) ) {
            $nome_file = $filePath.$row[0].'_'.$row[1];

            $hash = file_exists($nome_file) ? md5_file($nome_file) : '';
            $db->executeUpdate($query, array($hash, $row[0]));

            echo $row[0], '_', $row[1], ': ', strlen($hash) > 0 ? $hash : 'not found', PHP_EOL;
        }

        $res = null;
    }
}
