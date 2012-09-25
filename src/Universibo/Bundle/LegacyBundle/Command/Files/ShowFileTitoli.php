<?php
namespace Universibo\Bundle\LegacyBundle\Command\Files;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItem;

use Universibo\Bundle\LegacyBundle\Framework\PluginCommand;

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

class ShowFileTitoli extends PluginCommand
{

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

        $bc = $this->getBaseCommand();
        $user = $bc->get('security.context')->getToken()->getUser();
        $canale = $bc->getRequestCanale();
        $fc = $bc->getFrontController();
        $template = $fc->getTemplateEngine();
        $krono = $fc->getKrono();

        $id_canale = $canale->getIdCanale();
        $titolo_canale = $canale->getTitolo();
        $ultima_modifica_canale = $canale->getUltimaModifica();
        $user_ruoli = $user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();
        $lastLogin = $user instanceof User ? $user->getLastLogin()->getTimestamp() : 0;

        $personalizza_not_admin = false;

        $template->assign('showFileTitoli_addFileFlag', 'false');
        if (array_key_exists($id_canale, $user_ruoli) || $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            $personalizza = true;

            if (array_key_exists($id_canale, $user_ruoli)) {
                $ruolo = $user_ruoli[$id_canale];

                $personalizza_not_admin = true;
                $referente = $ruolo->isReferente();
                $moderatore = $ruolo->isModeratore();
                $ultimo_accesso = $ruolo->getUltimoAccesso();
            }

            if ($this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || $moderatore) {
                $template->assign('showFileTitoli_addFileFlag', 'true');
                $template
                        ->assign('showFileTitoli_addFile',
                                'Invia un nuovo file');
                $template
                        ->assign('showFileTitoli_addFileUri',
                                '/?do=FileAdd&id_canale=' . $id_canale);
                $template->assign('showFileTitoli_adminFileFlag', 'true');
                $template->assign('showFileTitoli_adminFile', 'Gestione file');
                $template
                        ->assign('showFileTitoli_adminFileUri',
                                '/?do=FileDocenteAdmin&id_canale='
                                        . $id_canale);
            }
        } else {
            $personalizza = false;
            $referente = false;
            $moderatore = false;
            $ultimo_accesso = $lastLogin;
        }
        /*
                $canale_news = $this->getNumNewsCanale($id_canale);

                $template->assign('showNews_desc', 'Mostra le ultime '.$num_news.' notizie del canale '.$id_canale.' - '.$titolo_canale);
         */
        //		var_dump($elenco_id_news);
        //		die();
        //		var_dump($elenco_id_news);
        //		die();

        $elenco_id_file = $this->getFileCanale($id_canale);

        //var_dump($elenco_id_file); die();
        $elenco_file = FileItem::selectFileItems($elenco_id_file);
        usort($elenco_file, array($this, '_compareFile'));

        //var_dump($elenco_file); die();

        //$elenco_categorie_file_tpl = array();
        $categorie_tpl = array();
        $elenco_file_tpl = array();

        if ($elenco_file == !false) {
            $ret_file = count($elenco_file);

            for ($i = 0; $i < $ret_file; $i++) {

                $file = $elenco_file[$i];
                //var_dump($file);
                $this_moderatore = ($this->get('security.context')->isGranted('ROLE_ADMIN')
                        || ($moderatore
                                && $file->getIdUtente() == $user->getId()));
                
                $groups = $user instanceof User ? $user->getGroups() : 1;

                $permessi_lettura = $file->getPermessiVisualizza();
                if ($permessi_lettura & $groups) {
                    $file_tpl = array();
                    $file_tpl['titolo'] = $file->getTitolo();
                    //$file_tpl['notizia']      = $file->getNotizia();
                    $file_tpl['data'] = $krono
                            ->k_date('%j/%m/%Y', $file->getDataInserimento());
                    //echo $personalizza,"-" ,$ultimo_accesso,"-", $file->getUltimaModifica()," -- ";
                    //$file_tpl['nuova']        = ($flag_chkDiritti && $personalizza_not_admin && $ultimo_accesso < $file->getUltimaModifica()) ? 'true' : 'false';
                    $file_tpl['nuova'] = ($personalizza_not_admin
                            && $ultimo_accesso < $file->getDataModifica()) ? 'true'
                            : 'false';
                    $file_tpl['autore'] = $file->getUsername();
                    $file_tpl['autore_link'] = '/?do=ShowUser&id_utente='
                            . $file->getIdUtente();
                    $file_tpl['id_autore'] = $file->getIdUtente();
                    $file_tpl['modifica'] = '';
                    $file_tpl['modifica_link'] = '';
                    $file_tpl['elimina'] = '';
                    $file_tpl['elimina_link'] = '';
                    //if ( ($this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || $this_moderatore)  && $flag_chkDiritti)
                    if (($this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || $this_moderatore)) {
                        $file_tpl['modifica'] = 'Modifica';
                        $file_tpl['modifica_link'] = '/?do=FileEdit&id_file='
                                . $file->getIdFile() . '&id_canale='
                                . $id_canale;
                        $file_tpl['elimina'] = 'Elimina';
                        $file_tpl['elimina_link'] = '/?do=FileDelete&id_file='
                                . $file->getIdFile() . '&id_canale='
                                . $id_canale;
                    }
                    $file_tpl['dimensione'] = $file->getDimensione();
                    //	tolto controllo: Il link download va mostrato sempre, il controllo ? effettuato successivamente
                    //					$file_tpl['download_uri'] = '';
                    //					$permessi_download = $file->getPermessiDownload();
                    //					if ($user->isGroupAllowed($permessi_download))
                    $file_tpl['download_uri'] = '/?do=FileDownload&id_file='
                            . $file->getIdFile() . '&id_canale=' . $id_canale;
                    $file_tpl['categoria'] = $file->getCategoriaDesc();
                    $file_tpl['show_info_uri'] = '/?do=FileShowInfo&id_file='
                            . $file->getIdFile() . '&id_canale=' . $id_canale;

                    if (!array_key_exists($file->getIdCategoria(),
                            $elenco_file_tpl))
                        $elenco_file_tpl[$file->getIdCategoria()]['desc'] = $file
                                ->getCategoriaDesc();

                    $elenco_file_tpl[$file->getIdCategoria()]['file'][$i] = $file_tpl;

                }
            }
        }

        $num_file = count($elenco_file_tpl);
        if ($num_file == 0) {
            $template
                    ->assign('showFileTitoli_langFileAvailable',
                            'Non ci sono file da visualizzare');
            $template->assign('showFileTitoli_langFileAvailableFlag', 'false');
        } else {
            $template
                    ->assign('showFileTitoli_langFileAvailable',
                            'Ci sono ' . $num_file . ' file');
            $template->assign('showFileTitoli_langFileAvailableFlag', 'true');
        }

        $template->assign('showFileTitoli_fileList', $elenco_file_tpl);

    }

    /**
     * Preleva da database i file del canale $id_canale
     *
     * @param  int   $id_canale identificativo su database del canale
     * @return array elenco FileItem , array vuoto se non ci sono file
     */
    public function getFileCanale($channelId)
    {
        $fileRepo = $this->getContainer()->get('universibo_legacy.repository.files.file_item');

        return $fileRepo->findIdByChannel($channelId);
    }

    /**
     * Ordina la struttura dei file
     *
     * @static
     * @private
     */
    public function _compareFile($a, $b)
    {
        if ($a->getIdCategoria() > $b->getIdCategoria())
            return +1;
        if ($a->getIdCategoria() < $b->getIdCategoria())
            return -1;
        if ($a->getDataInserimento() < $b->getDataInserimento())
            return +1;
        if ($a->getDataInserimento() > $b->getDataInserimento())
            return -1;
    }

}
