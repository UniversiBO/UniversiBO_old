<?php
namespace UniversiBO\Bundle\LegacyBundle\Command\Files;

use UniversiBO\Bundle\LegacyBundle\Entity\Files\FileItemStudenti;

use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Entity\Files\FileItem;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;
use UniversiBO\Bundle\LegacyBundle\Framework\PluginCommand;

/**
 * ShowFileTitoli ? un'implementazione di PluginCommand.
 *
 * Mostra i file del canale
 * Il BaseCommand che chiama questo plugin deve essere un'implementazione di CanaleCommand.
 *
 *
 * @package universibo
 * @subpackage News
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ShowFileStudentiTitoli extends PluginCommand
{
    //todo: rivedere la questione diritti per uno studente...

    /**
     * Esegue il plugin
     *
     * @param array $param nessu parametro
     */
    public function execute($param = array())
    {
        //$flag_chkDiritti	=  $param['chk_diritti'];
//		var_dump($param['id_notizie']);
//		die();
        $num = $param['num'];
        $bc        = $this->getBaseCommand();
        $user      = $bc->getSessionUser();
        $canale    = $bc->getRequestCanale();
        $fc        = $bc->getFrontController();
        $template  = $fc->getTemplateEngine();
        $krono     = $fc->getKrono();

        $files_studenti_attivo = $canale->getServizioFilesStudenti();

        if ( !$files_studenti_attivo ) {
            $template->assign('showFileStudentiTitoli_langFileAvailableFlag', 'false');

            return;
        }

        $id_canale = $canale->getIdCanale();
        $titolo_canale =  $canale->getTitolo();
        $ultima_modifica_canale =  $canale->getUltimaModifica();
        $user_ruoli = $user->getRuoli();

        $personalizza_not_admin = false;

        $template->assign('showFileStudentiTitoli_addFileFlag', 'false');
            if (array_key_exists($id_canale, $user_ruoli) || $user->isAdmin()) {
                $personalizza = true;

                if (array_key_exists($id_canale, $user_ruoli)) {
                    $ruolo = $user_ruoli[$id_canale];

                    $personalizza_not_admin = true;
                    $referente      = $ruolo->isReferente();
                    $moderatore     = $ruolo->isModeratore();
                    $ultimo_accesso = $ruolo->getUltimoAccesso();
                }

//				if ( !$user->isOspite() )
//				{
//					$template->assign('showFileStudentiTitoli_addFileFlag', 'true');
//					$template->assign('showFileStudentiTitoli_addFile', 'Inserisci il tuo contributo');
//					$template->assign('showFileStudentiTitoli_addFileUri', 'v2.php?do=FileStudentiAdd&id_canale='.$id_canale);
//				}
            } else {
                $personalizza   = false;
                $referente      = false;
                $moderatore     = false;
                $ultimo_accesso = $user->getUltimoLogin();
            }

            //Solo se quello che naviga non e` loggato, non compare il link

            if ( !$user->isOspite() ) {
                    $template->assign('showFileStudentiTitoli_addFileFlag', 'true');
                    $template->assign('showFileStudentiTitoli_addFile', 'Inserisci il tuo contributo');
                    $template->assign('showFileStudentiTitoli_addFileUri', 'v2.php?do=FileStudentiAdd&id_canale='.$id_canale);
                }
/*
        $canale_news = $this->getNumNewsCanale($id_canale);

        $template->assign('showNews_desc', 'Mostra le ultime '.$num_news.' notizie del canale '.$id_canale.' - '.$titolo_canale);
*/

        $elenco_id_file = $this->getFileCanale($id_canale);

        //var_dump($elenco_id_file); die();
        $elenco_file = FileItemStudenti::selectFileItems($elenco_id_file);
        usort($elenco_file, array($this,'_compareFile'));

        //var_dump($elenco_file); die();

        //$elenco_categorie_file_tpl = array();
        $categorie_tpl = array();
        $elenco_file_tpl = array();

        if ($elenco_file ==! false ) {
            $ret_file = count($elenco_file);

            for ($i = 0; $i < $ret_file; $i++) {

                $file = $elenco_file[$i];
                //var_dump($file);
                $this_moderatore = ($user->isAdmin() || ($moderatore && $file->getIdUtente()==$user->getIdUser()));

                $permessi_lettura = $file->getPermessiVisualizza();
                if ($user->isGroupAllowed($permessi_lettura)) {
                    $file_tpl = array();
                    $file_tpl['titolo']       = $file->getTitolo();
                    //$file_tpl['notizia']      = $file->getNotizia();
                    $file_tpl['data']         = $krono->k_date('%j/%m/%Y', $file->getDataInserimento());
                    //echo $personalizza,"-" ,$ultimo_accesso,"-", $file->getUltimaModifica()," -- ";
                    //$file_tpl['nuova']        = ($flag_chkDiritti && $personalizza_not_admin && $ultimo_accesso < $file->getUltimaModifica()) ? 'true' : 'false';
                    $file_tpl['nuova']        = ($personalizza_not_admin && $ultimo_accesso < $file->getDataModifica()) ? 'true' : 'false';
                    $file_tpl['autore']       = $file->getUsername();
                    $file_tpl['autore_link']  = 'v2.php?do=ShowUser&id_utente='.$file->getIdUtente();
                    $file_tpl['id_autore']    = $file->getIdUtente();
                    $file_tpl['modifica']     = '';
                    $file_tpl['modifica_link']= '';
                    $file_tpl['elimina']      = '';
                    $file_tpl['elimina_link'] = '';
                    //if ( ($user->isAdmin() || $referente || $this_moderatore)  && $flag_chkDiritti)
                    if (($user->isAdmin() || $referente || $this_moderatore || ($user == $file->getIdUtente()))) {
                        $file_tpl['modifica']     = 'Modifica';
                        $file_tpl['modifica_link']= 'v2.php?do=FileEdit&id_file='.$file->getIdFile().'&id_canale='.$id_canale;
                        $file_tpl['elimina']      = 'Elimina';
                        $file_tpl['elimina_link'] = 'v2.php?do=FileDelete&id_file='.$file->getIdFile().'&id_canale='.$id_canale;
                    }
                    $file_tpl['dimensione'] = $file->getDimensione();
//	tolto controllo: Il link download va mostrato sempre, il controllo ? effettuato successivamente
//					$file_tpl['download_uri'] = '';
//					$permessi_download = $file->getPermessiDownload();
//					if ($user->isGroupAllowed($permessi_download))
                    $file_tpl['download_uri'] = 'v2.php?do=FileDownload&id_file='.$file->getIdFile().'&id_canale='.$id_canale;
                    $file_tpl['categoria'] = $file->getCategoriaDesc();
                    $file_tpl['show_info_uri'] = 'v2.php?do=FileShowInfo&id_file='.$file->getIdFile().'&id_canale='.$id_canale;

                    if (!array_key_exists($file->getIdCategoria(), $elenco_file_tpl))
                        $elenco_file_tpl[$file->getIdCategoria()]['desc'] = $file->getCategoriaDesc();

                    $elenco_file_tpl[$file->getIdCategoria()]['file'][$i] = $file_tpl;

                }
            }
        }

        $num_file = count($elenco_file_tpl);

            $template->assign('showFileStudentiTitoli_langFileAvailable', 'Ci sono '.$num_file.' file');
            $template->assign('showFileStudentiTitoli_langFileAvailableFlag', 'true');
            $template->assign('showFileStudentiTitoli_fileList', $elenco_file_tpl);
    }

    /**
     * Preleva da database i file del canale $id_canale
     *
     * @static
     * @param  int   $id_canale identificativo su database del canale
     * @return array elenco FileItem , array vuoto se non ci sono file
     */
    public function getFileCanale($id_canale)
    {

         $db = FrontController::getDbConnection('main');

        $query = 'SELECT A.id_file  FROM file A, file_studente_canale B
                    WHERE A.id_file = B.id_file AND eliminato!='.$db->quote( FILE_ELIMINATO ).
                    ' AND B.id_canale = '.$db->quote($id_canale).' AND A.data_inserimento < '.$db->quote(time()).
                    'ORDER BY A.id_categoria, A.data_inserimento DESC';
        $res = $db->query($query);

        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        $id_file_list = array();

        while ( $res->fetchInto($row) ) {
            $id_file_list[]= $row[0];
        }

        $res->free();

        return $id_file_list;

    }

    /**
     * Ordina la struttura dei file
     *
     * @static
     * @private
     */
    public function _compareFile($a, $b)
    {
        if ($a->getIdCategoria() > $b->getIdCategoria()) return +1;
        if ($a->getIdCategoria() < $b->getIdCategoria()) return -1;
        if ($a->getDataInserimento() < $b->getDataInserimento()) return +1;
        if ($a->getDataInserimento() > $b->getDataInserimento()) return -1;
    }

}

