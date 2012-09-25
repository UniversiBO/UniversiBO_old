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
        if ($user->isOspite())
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => 'La pagina e` visualizzabile solo dopo aver fatto il login. La vostra sessione potrebbe essere scaduta.',
                            'file' => __FILE__, 'line' => __LINE__));

        $idUtente = $user->getIdUser();

        $listaFile = FileItem::selectFileItemsByIdUtente($idUtente, true);

        $files = array();
        foreach ($listaFile as &$item)
            $files[$item->getIdFile()] = array('nome' => $item->getNomeFile(),
                    'data' => $item->getDataInserimento(),
                    'dimensione' => $item->getDimensione(),
                    'editUri' => '/?do=FileEdit&id_file='
                            . $item->getIdFile(),
                    'deleteUri' => '/?do=FileDelete&id_file='
                            . $item->getIdFile(),
                    'downloadUri' => '/?do=FileDelete&id_file='
                            . $item->getIdFile());

        $template->assign('ShowPersonalFiles_listaFile', $files);
        $template
                ->assign('ShowPersonalFiles_langTitle', 'Gestisci i tuoi file');

    }
}
