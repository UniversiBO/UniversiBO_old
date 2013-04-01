<?php
namespace Universibo\Bundle\LegacyBundle\Command\Files;

use Universibo\Bundle\MainBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Framework\PluginCommand;

/**
 * ShowAllFilesStudentiTitoli e` un'implementazione di PluginCommand.
 *
 * Mostra i file del canale
 * Il BaseCommand che chiama questo plugin deve essere un'implementazione di CanaleCommand.
 *
 *
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ShowAllFilesStudentiTitoli extends PluginCommand
{
    /**
     * Esegue il plugin
     *
     * @param array $param nessu parametro
     */
    public function execute($param = array())
    {
        $elenco_file = $param['files'];
        $bc        = $this->getBaseCommand();
        $user      = $bc->get('security.context')->getToken()->getUser();
        $fc        = $bc->getFrontController();
        $template  = $fc->getTemplateEngine();
        $krono     = $fc->getKrono();
        $router    = $this->get('router');

        $personalizza   = false;
        $referente      = false;
        $moderatore     = false;
        $personalizza_not_admin = false;
        $ultimo_accesso = $user instanceof User ? $user->getLastLogin()->getTimestamp() : 0;
        $groups = $user instanceof User ? $user->getLegacyGroups() : 1;

        $canale_files = count($elenco_file);

        if ($canale_files == 0) {
            $template->assign('showAllFilesStudentiTitoli_langFileAvailable', 'Non ci sono files da visualizzare');
            $template->assign('showAllFilesStudentiTitoli_langFileAvailableFlag', 'false');
        } else {
            $template->assign('showAllFilesStudentiTitoli_langFileAvailable', 'Ci sono '.$canale_files.' notizie');
            $template->assign('showAllFilesStudentiTitoli_langFileAvailableFlag', 'true');
        }

//		usort($elenco_file, array('ShowMyFileTitoli','_compareFile'));

        //$elenco_categorie_file_tpl = array();
        $categorie_tpl = array();
        $file_tpl = array();

        if ($elenco_file ==! false) {
            $ret_file = count($elenco_file);

            $channelRouter = $this->get('universibo_legacy.routing.channel');
            for ($i = 0; $i < $ret_file; $i++) {

                $file = $elenco_file[$i];
                //var_dump($file);
                $this_moderatore = ($this->get('security.context')->isGranted('ROLE_ADMIN') || ($moderatore && $file->getIdUtente()==$user->getId()));

                $permessi_lettura = $file->getPermessiVisualizza();
                if ($permessi_lettura & $groups) {

                    $file_tpl[$i]['titolo']       = $file->getTitolo();
                    //$file_tpl['notizia']      = $file->getNotizia();
                    $file_tpl[$i]['data']         = $krono->k_date('%j/%m/%Y', $file->getDataInserimento());
                    //echo $personalizza,"-" ,$ultimo_accesso,"-", $file->getUltimaModifica()," -- ";
                    //$file_tpl['nuova']        = ($flag_chkDiritti && $personalizza_not_admin && $ultimo_accesso < $file->getUltimaModifica()) ? 'true' : 'false';
                    $file_tpl[$i]['nuova']        = ($personalizza_not_admin && $ultimo_accesso < $file->getDataModifica()) ? 'true' : 'false';
                    $file_tpl[$i]['autore']       = $file->getUsername();
                    $file_tpl[$i]['autore_link']  = $router->generate('universibo_legacy_user', array('id_utente' => $file->getIdUtente()));
                    $file_tpl[$i]['id_autore']    = $file->getIdUtente();
                    $file_tpl[$i]['dimensione'] = $file->getDimensione();
                    $file_tpl[$i]['voto_medio'] = round($file->getVoto($file->getIdFile()),1);
//	tolto controllo: Il link download va mostrato sempre, il controllo ? effettuato successivamente
//					$file_tpl['download_uri'] = '';
//					$permessi_download = $file->getPermessiDownload();
//					if ($user->isGroupAllowed($permessi_download))
                    $canali = $file->getIdCanali();
                    $num_canali =  count($canali);
                    $elenco_canali_tpl = array();

                    for ($j = 0; $j < $num_canali; $j++) {
                        $canale = Canale::retrieveCanale($canali[$j]);
                        if ($canale->isGroupAllowed($groups)) {
                            $canale_tpl = array();
                            $file_tpl[$i]['canaleTitolo'] = $canale->getNome();

                            $file_tpl[$i]['canaleLink'] = $channelRouter->generate($canale);
                            $file_tpl[$i]['download_uri'] = $router->generate('universibo_legacy_file_download', array('id_file' => $file->getIdFile(), 'id_canale' => $canali[$j]));
                            $file_tpl[$i]['show_info_uri'] = $router->generate('universibo_legacy_file', array('id_file' => $file->getIdFile(), 'id_canale' => $canali[$j]));
                            $file_tpl[$i]['canali'][] = $canale_tpl;
                        }
                    }

                    $file_tpl[$i]['categoria'] = $file->getCategoriaDesc();
                    $file_tpl[$i]['modifica']     = '';
                    $file_tpl[$i]['modifica_link']= '';
                    $file_tpl[$i]['elimina']      = '';
                    $file_tpl[$i]['elimina_link'] = '';
                    $file_tpl[$i]['desc'] = $file->getCategoriaDesc();
                    //roba mia

                }
            }
        }
        $template->assign('showAllFilesStudentiTitoli_fileList', $file_tpl);
    }

    /**
     * Ordina la struttura dei file
     *
     * @static
     * @private
     */
    public function _compareFile($a, $b)
    {
//		if ($a->getIdCategoria() > $b->getIdCategoria()) return +1;
//		if ($a->getIdCategoria() < $b->getIdCategoria()) return -1;
//		if ($a->getDataInserimento() < $b->getDataInserimento()) return +1;
//		if ($a->getDataInserimento() > $b->getDataInserimento()) return -1;
    }
}
