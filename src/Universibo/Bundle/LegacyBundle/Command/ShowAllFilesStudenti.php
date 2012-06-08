<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use \Error;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Framework\FrontController;

/**
 * ShowAllFilesStudenti e\' un comando che permette di visualizzare tutti i
 * files studenti presenti su UniversiBO
 *
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Daniele Tiles <daniele.tiles@gmail.com>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ShowAllFilesStudenti extends UniversiboCommand
{
    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $user = $this->getSessionUser();
        $arrayFilesStudenti = array();

        if (!array_key_exists('order', $_GET) || !preg_match('/^([0-9]{1,9})$/', $_GET['order'] ) || ($_GET['order'] > 2) ) {
            Error::throwError(_ERROR_DEFAULT,array('id_utente' => $user->getIdUser(), 'msg'=>'L\'ordine richiesto non e` valido','file'=>__FILE__,'line'=>__LINE__ ));
        }
        $order = $_GET['order'];

        $arrayFilesStudenti = $this->getAllFiles($order);
        $this->executePlugin('ShowAllFilesStudentiTitoli', array('files'=>$arrayFilesStudenti,'chk_diritti'=>false));
        switch ($order) {
            case 0:
                $template->assign('showAllFilesStudenti_titoloPagina','ordinati per nome');
                $template->assign('showAllFilesStudenti_url1','v2.php?do=ShowAllFilesStudenti&order=1');
                $template->assign('showAllFilesStudenti_lang1','Mostra i Files Studenti ordinati per data di inserimento');
                $template->assign('showAllFilesStudenti_url2','v2.php?do=ShowAllFilesStudenti&order=2');
                $template->assign('showAllFilesStudenti_lang2','Mostra i Files Studenti ordinati per voto medio');
                break;

            case 1:
                $template->assign('showAllFilesStudenti_titoloPagina','ordinati per data di inserimento');
                $template->assign('showAllFilesStudenti_url1','v2.php?do=ShowAllFilesStudenti&order=0');
                $template->assign('showAllFilesStudenti_lang1','Mostra i Files Studenti ordinati per nome');
                $template->assign('showAllFilesStudenti_url2','v2.php?do=ShowAllFilesStudenti&order=2');
                $template->assign('showAllFilesStudenti_lang2','Mostra i Files Studenti ordinati per voto medio');
                break;

            case 2:
                $template->assign('showAllFilesStudenti_titoloPagina','ordinati per voto medio');
                $template->assign('showAllFilesStudenti_url1','v2.php?do=ShowAllFilesStudenti&order=0');
                $template->assign('showAllFilesStudenti_lang1','Mostra i Files Studenti ordinati per nome');
                $template->assign('showAllFilesStudenti_url2','v2.php?do=ShowAllFilesStudenti&order=1');
                $template->assign('showAllFilesStudenti_lang2','Mostra i Files Studenti ordinati per data di inserimento');
                break;
        }

    }

    public function getAllFiles($order)
    {
        return $this->getContainer()->get('universibo_legacy.repository.files.file_item_studenti')->findAll($order);
    }
}
