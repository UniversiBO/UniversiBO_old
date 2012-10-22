<?php
namespace Universibo\Bundle\LegacyBundle\Command\Files;

use Error;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItem;
use Universibo\Bundle\LegacyBundle\Entity\Files\FileItemStudenti;
use Universibo\Bundle\LegacyBundle\Framework\PluginCommand;

/**
 * ShowFileInfo: mostra tutte le informazioni correlate ad un file
 *
 * @package universibo
 * @subpackage Files
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ShowFileInfo extends PluginCommand
{

    /**
     * Esegue il plugin
     *
     * @param array $param id_file obbligatorio, id_canale facoltativo
     */
    public function execute($param = array())
    {
        $bc = $this->getBaseCommand();
        $router = $this->get('router');
        $user = $bc->get('security.context')->getToken()->getUser();
        $userId = $user instanceof User ? $user->getId() : 0;

        if (!array_key_exists('id_file', $param)
                || !preg_match('/^([0-9]{1,9})$/', $param['id_file'])) {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $userId,
                            'msg' => 'L\'id del file richiesto non e` valido',
                            'file' => __FILE__, 'line' => __LINE__));
        }

        $fc = $bc->getFrontController();
        $template = $fc->getTemplateEngine();
        $krono = $fc->getKrono();

        $template
                ->assign('common_canaleURI',
                        array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER']
                                : '');
        $template->assign('common_langCanaleNome', 'indietro');

        $tipo_file = FileItemStudenti::isFileStudenti($param['id_file']);

        if ($tipo_file)
            $file = FileItemStudenti::selectFileItem($param['id_file']);
        else
            $file = FileItem::selectFileItem($param['id_file']);
        //Con questo passaggio dovrei riuscire a verificare se il file che si vuole modificare è un file studente o no
        //true -> è un file studente
        //		var_dump($tipo_file);
        //		die();

        if ($file === false)
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $userId,
                            'msg' => "Il file richiesto non e` presente su database",
                            'file' => __FILE__, 'line' => __LINE__));

        //var_dump($file);
        $directoryFile = $fc->getAppSetting('filesPath');
        $nomeFile = $file->getIdFile() . '_' . $file->getNomeFile();
        $groups = $user instanceof User ? $user->getLegacyGroups() : 1;

        if (!$file->getPermessiVisualizza() & $groups)
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user instanceof User ? $user->getId() : 0,
                            'msg' => 'Non e` permesso visualizzare il file.
            Non possiedi i diritti necessari, la sessione potrebbe essere scaduta.',
                            'file' => __FILE__, 'line' => __LINE__,
                            'log' => true));

        $template->assign('showFileInfo_editFlag', 'false');
        $template->assign('showFileInfo_deleteFlag', 'false');
        $referente = false;
        $moderatore = false;
        $parametro_canale = '';

        $params = array('id_file' => $file->getIdFile());

        if ($id_canale = $this->getBaseCommand()->getRequest()->get('id_canale')) {
            if (!preg_match('/^([0-9]{1,9})$/', $id_canale))
                Error::throwError(_ERROR_DEFAULT,
                        array(
                                'msg' => 'L\'id del canale richiesto non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));

            $canale = Canale::retrieveCanale($id_canale);
            if ($canale->getServizioFiles() == false)
                Error::throwError(_ERROR_DEFAULT,
                        array('msg' => "Il servizio files e` disattivato",
                                'file' => __FILE__, 'line' => __LINE__));

            $params['id_canale'] = $id_canale;

            $user_ruoli = $canale->getRuoli();
            $template->assign('common_canaleURI', $canale->showMe($router));
            $template
                    ->assign('common_langCanaleNome',
                            'a ' . $canale->getTitolo());
            if (array_key_exists($id_canale, $user_ruoli)) {
                $ruolo = &$user_ruoli[$id_canale];

                $referente = $ruolo->isReferente();
                $moderatore = $ruolo->isModeratore();
            }
            //controllo coerenza parametri
            $canali_file = $file->getIdCanali();
            if (!in_array($id_canale, $canali_file)) {
                Error::throwError(_ERROR_DEFAULT,
                        array(
                                'msg' => 'I parametri passati non sono coerenti',
                                'file' => __FILE__, 'line' => __LINE__));
            }
        }

        $autore = $user instanceof User && ($user->getId() == $file->getIdUtente());
        if ($autore || $this->get('security.context')->isGranted('ROLE_ADMIN') || $referente || $moderatore) {
            $template->assign('showFileInfo_editFlag', 'true');
            $template->assign('showFileInfo_deleteFlag', 'true');

            if ($tipo_file) {
                $template->assign('showFileInfo_editUri', $router->generate('universibo_legacy_file_studenti_edit', array('id_file' => $file->getIdFile(), 'id_canale' => $id_canale)));
                $template->assign('showFileInfo_deleteUri', $router->generate('universibo_legacy_file_studenti_delete', array('id_file' => $file->getIdFile(), 'id_canale' => $id_canale)));
            } else {
                $template->assign('showFileInfo_editUri', $router->generate('universibo_legacy_file_edit', array('id_file' => $file->getIdFile(), 'id_canale' => $id_canale)));
                $template->assign('showFileInfo_deleteUri', $router->generate('universibo_legacy_file_delete', array('id_file' => $file->getIdFile(), 'id_canale' => $id_canale)));
            }
        }

        if ($tipo_file) {
            $voto = FileItemStudenti::getVoto($param['id_file']);
            //			var_dump($voto);
            //			die();
            if ($voto == NULL)
                $voto = 'Non esistono ancora voti per questo file';
            else
                $voto = round($voto, 1);
            $template->assign('showFileInfo_voto', $voto);
            $template->assign('showFileInfo_addComment', $router->generate('universibo_legacy_file_studenti_comment', array('id_file' => $file->getIdFile())));
        }

        $canali_tpl = array();
        $id_canali = $file->getIdCanali();
        foreach ($id_canali as $id_canale) {
            $canale = Canale::retrieveCanale($id_canale);
            $canali_tpl[$id_canale] = array();
            $canali_tpl[$id_canale]['titolo'] = $canale->getTitolo();
            $canali_tpl[$id_canale]['uri'] = $canale->showMe($router);
        }

        $template->assign('showFileInfo_downloadUri', $router->generate('universibo_legacy_file_download', array('id_file' => $file->getIdFile())));
        $template->assign('showFileInfo_langDelete', 'Elimina');
        $template->assign('showFileInfo_langDownload', 'Scarica');
        $template->assign('showFileInfo_langEdit', 'Modifica');
        $template->assign('showFileInfo_uri', $router->generate('universibo_legacy_file', array('id_file' => $file->getIdFile())));
        $template->assign('showFileInfo_titolo', $file->getTitolo());
        $template->assign('showFileInfo_descrizione', $file->getDescrizione());
        $template->assign('showFileInfo_userLink', $router->generate('universibo_legacy_user', array('id_utente' => $file->getIdUtente())));
        $template->assign('showFileInfo_username', $file->getUsername());
        $template->assign('showFileInfo_dataInserimento',$krono->k_date('%j/%m/%Y', $file->getDataInserimento()));

        $template->assign('showFileInfo_new', ($user instanceof User && $file->getDataModifica() < $user->getLastLogin()->getTimestamp()) ? 'true' : 'false');
        $template->assign('showFileInfo_nomeFile', $nomeFile);
        $template->assign('showFileInfo_dimensione', $file->getDimensione());
        $template->assign('showFileInfo_download', $file->getDownload());
        $template->assign('showFileInfo_hash', $file->getHashFile());
        $template->assign('showFileInfo_categoria', $file->getCategoriaDesc());
        $template->assign('showFileInfo_tipo', $file->getTipoDesc());
        $template->assign('showFileInfo_icona', $fc->getAppSetting('filesTipoIconePath'). $file->getTipoIcona());
        $template->assign('showFileInfo_info', $file->getTipoInfo());
        $template->assign('showFileInfo_canali', $canali_tpl);
        $template->assign('showFileInfo_paroleChiave', $file->getParoleChiave());
        $template->assign('isFileStudente', (($tipo_file == true) ? 'true' : 'false'));
    }
}
