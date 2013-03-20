<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * ShowAllFilesStudenti e\' un comando che permette di visualizzare tutti i
 * files studenti presenti su UniversiBO
 *
 *
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
        $router = $this->get('router');

        $request = $this->getRequest();
        $order = $request->get('order', 0);


        $arrayFilesStudenti = $this->getAllFiles($order);
        $this->executePlugin('ShowAllFilesStudentiTitoli', array('files'=>$arrayFilesStudenti,'chk_diritti'=>false));
        switch ($order) {
            case 0:
                $template->assign('showAllFilesStudenti_titoloPagina','ordinati per nome');
                $template->assign('showAllFilesStudenti_url1', $router->generate('universibo_legacy_file_studenti', array('order' => 1)));
                $template->assign('showAllFilesStudenti_lang1','Mostra i Files Studenti ordinati per data di inserimento');
                $template->assign('showAllFilesStudenti_url2',$router->generate('universibo_legacy_file_studenti', array('order' => 2)));
                $template->assign('showAllFilesStudenti_lang2','Mostra i Files Studenti ordinati per voto medio');
                break;

            case 1:
                $template->assign('showAllFilesStudenti_titoloPagina','ordinati per data di inserimento');
                $template->assign('showAllFilesStudenti_url1',$router->generate('universibo_legacy_file_studenti', array('order' => 0)));
                $template->assign('showAllFilesStudenti_lang1','Mostra i Files Studenti ordinati per nome');
                $template->assign('showAllFilesStudenti_url2',$router->generate('universibo_legacy_file_studenti', array('order' => 2)));
                $template->assign('showAllFilesStudenti_lang2','Mostra i Files Studenti ordinati per voto medio');
                break;

            case 2:
                $template->assign('showAllFilesStudenti_titoloPagina','ordinati per voto medio');
                $template->assign('showAllFilesStudenti_url1',$router->generate('universibo_legacy_file_studenti', array('order' => 0)));
                $template->assign('showAllFilesStudenti_lang1','Mostra i Files Studenti ordinati per nome');
                $template->assign('showAllFilesStudenti_url2',$router->generate('universibo_legacy_file_studenti', array('order' => 1)));
                $template->assign('showAllFilesStudenti_lang2','Mostra i Files Studenti ordinati per data di inserimento');
                break;
        }

    }

    public function getAllFiles($order)
    {
        return $this->getContainer()->get('universibo_legacy.repository.files.file_item_studenti')->findAll($order);
    }
}
