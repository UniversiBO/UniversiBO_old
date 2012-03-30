<?php

use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;

require_once 'Files/FileItemStudenti'.PHP_EXTENSION;

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
    function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $user = $this->getSessionUser();

        // controllo che l'utente sia loggato
        if($user->isOspite())
            Error::throwError(_ERROR_DEFAULT,array('id_utente' => $user->getIdUser(), 'msg'=>'La pagina è visualizzabile solo dopo aver fatto il login. La vostra sessione potrebbe essere scaduta.','file'=>__FILE__,'line'=>__LINE__ ));

        $idUtente = $user->getIdUser();

        $listaFile = & FileItem::selectFileItemsByIdUtente($idUtente, true);

        $files = array();
        foreach ($listaFile as &$item)
            $files[$item->getIdFile()] = array ('nome' => $item->getNomeFile(),
                    'data' => $item->getDataInserimento(),
                    'dimensione'=> $item->getDimensione(),
                    'editUri' => 'index.php?do=FileEdit&id_file='.$item->getIdFile(),
                    'deleteUri' => 'index.php?do=FileDelete&id_file='.$item->getIdFile(),
                    'downloadUri' => 'index.php?do=FileDelete&id_file='.$item->getIdFile()
            );

        $template->assign('ShowPersonalFiles_listaFile', $files);
        $template->assign('ShowPersonalFiles_langTitle', 'Gestisci i tuoi file');

    }
}
