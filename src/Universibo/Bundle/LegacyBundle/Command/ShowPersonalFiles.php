<?php
namespace Universibo\Bundle\LegacyBundle\Command;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItem;

use \Error;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * ShowAllFilesStudenti e\' un comando che permette di visualizzare tutti i
 * files studenti presenti su UniversiBO
 *
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author barto
 * @author evaimitico
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ShowPersonalFiles extends UniversiboCommand
{
    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $user = $this->get('security.context')->getToken()->getUser();

        // controllo che l'utente sia loggato
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getId(),
                            'msg' => 'La pagina e` visualizzabile solo dopo aver fatto il login. La vostra sessione potrebbe essere scaduta.',
                            'file' => __FILE__, 'line' => __LINE__));

        $idUtente = $user->getId();

        $listaFile = FileItem::selectFileItemsByIdUtente($idUtente, true);

        $files = array();
        foreach ($listaFile as &$item)
            $files[$item->getIdFile()] = array(
                    'nome' => $item->getNomeFile(),
                    'data' => $item->getDataInserimento(),
                    'dimensione' => $item->getDimensione(),
                    'editUri' => $router->generate('universibo_legacy_file_edit', array('id_file' => $file->getIdFile())),
                    'deleteUri' => $router->generate('universibo_legacy_file_delete', array('id_file' => $file->getIdFile())),
                    'downloadUri' => $router->generate('universibo_legacy_file_download', array('id_file' => $file->getIdFile()))
            );

        $template->assign('ShowPersonalFiles_listaFile', $files);
        $template
                ->assign('ShowPersonalFiles_langTitle', 'Gestisci i tuoi file');

    }
}
