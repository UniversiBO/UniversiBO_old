<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * FileShowInfo: mostra tutte le informazioni correlate ad un file
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class FileShowInfo extends UniversiboCommand
{

    public function execute()
    {
        $frontcontroller = $this->getFrontController();

        $template = $frontcontroller->getTemplateEngine();
        $krono = $frontcontroller->getKrono();
        $user = $this->get('security.context')->getToken()->getUser();
        $userId = $user instanceof User ? $user->getId() : 0;

        if (!array_key_exists('id_file', $_GET) || !preg_match('/^([0-9]{1,9})$/', $_GET['id_file'])) {
            throw new NotFoundHttpException('Invalid file ID');
        }

        $id_file = $_GET['id_file'];
        $tipo_file = $this->get('universibo_legacy.repository.files.file_item_studenti')->isFileStudenti($id_file);

        if (array_key_exists('id_canale', $_GET) && preg_match('/^([0-9]{1,9})$/', $_GET['id_canale'])) {
            $this->executePlugin('ShowFileInfo', array('id_file' => $_GET['id_file'],
                                    'id_canale' => $_GET['id_canale']));
        } else
            $this->executePlugin('ShowFileInfo', array('id_file' => $_GET['id_file']));
        if ($tipo_file == true) {
            $template->assign('isFileStudente', 'true');
            $this
                    ->executePlugin('ShowFileStudentiCommenti',
                            array('id_file' => $id_file));
        } else
            $template->assign('isFileStudente', 'false');

        return;

    }
}
