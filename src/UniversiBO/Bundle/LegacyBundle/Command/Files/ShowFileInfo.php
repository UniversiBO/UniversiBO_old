<?php
namespace UniversiBO\Bundle\LegacyBundle\Command\Files;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Entity\Canale;
use UniversiBO\Bundle\LegacyBundle\Entity\Files\FileItemStudenti;
use UniversiBO\Bundle\LegacyBundle\Entity\Files\FileItem;
use UniversiBO\Bundle\LegacyBundle\Framework\PluginCommand;

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
        $user = $bc->getSessionUser();

        if (!array_key_exists('id_file', $param)
                || !preg_match('/^([0-9]{1,9})$/', $param['id_file'])) {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => 'L\'id del file richiesto non � valido',
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

        //		if (array_key_exists('id_canale', $param) && preg_match('/^([0-9]{1,9})$/', $param['id_canale']))
        //		{
        //			$canale = & Canale::retrieveCanale($param['id_canale']);
        //			$template->assign('common_canaleURI', $canale->showMe());
        //			$template->assign('common_langCanaleNome', 'a '.$canale->getTitolo());
        //		}

        $tipo_file = FileItemStudenti::isFileStudenti($param['id_file']);

        if ($tipo_file)
            $file = FileItemStudenti::selectFileItem($param['id_file']);
        else
            $file = FileItem::selectFileItem($param['id_file']);
        //Con questo passaggio dovrei riuscire a verificare se il file che si vuole modificare � un file studente o no
        //true -> � un file studente
        //		var_dump($tipo_file);
        //		die();

        if ($file === false)
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => "Il file richiesto non � presente su database",
                            'file' => __FILE__, 'line' => __LINE__));

        //var_dump($file);
        $directoryFile = $fc->getAppSetting('filesPath');
        $nomeFile = $file->getIdFile() . '_' . $file->getNomeFile();

        if (!$user->isGroupAllowed($file->getPermessiVisualizza()))
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => 'Non � permesso visualizzare il file.
            Non possiedi i diritti necessari, la sessione potrebbe essere scaduta.',
                            'file' => __FILE__, 'line' => __LINE__,
                            'log' => true));

        $template->assign('showFileInfo_editFlag', 'false');
        $template->assign('showFileInfo_deleteFlag', 'false');
        $referente = false;
        $moderatore = false;
        $parametro_canale = '';

        if (array_key_exists('id_canale', $_GET)) {
            if (!preg_match('/^([0-9]{1,9})$/', $_GET['id_canale']))
                Error::throwError(_ERROR_DEFAULT,
                        array(
                                'msg' => 'L\'id del canale richiesto non � valido',
                                'file' => __FILE__, 'line' => __LINE__));

            $canale = &Canale::retrieveCanale($_GET['id_canale']);
            if ($canale->getServizioFiles() == false)
                Error::throwError(_ERROR_DEFAULT,
                        array('msg' => "Il servizio files � disattivato",
                                'file' => __FILE__, 'line' => __LINE__));

            $id_canale = $canale->getIdCanale();

            $parametro_canale = '&id_canale=' . $id_canale;

            $user_ruoli = $canale->getRuoli();
            $template->assign('common_canaleURI', $canale->showMe());
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
            if (!in_array($id_canale, $canali_file))
                Error::throwError(_ERROR_DEFAULT,
                        array(
                                'msg' => 'I parametri passati non sono coerenti',
                                'file' => __FILE__, 'line' => __LINE__));
        }

        $autore = ($user->getIdUser() == $file->getIdUtente());
        if ($autore || $user->isAdmin() || $referente || $moderatore) {
            $template->assign('showFileInfo_editFlag', 'true');
            $template->assign('showFileInfo_deleteFlag', 'true');
            if ($tipo_file) {
                $template
                        ->assign('showFileInfo_editUri',
                                'v2.php?do=FileStudentiEdit&id_file='
                                        . $file->getIdFile()
                                        . $parametro_canale);
                $template
                        ->assign('showFileInfo_deleteUri',
                                'v2.php?do=FileStudentiDelete&id_file='
                                        . $file->getIdFile()
                                        . $parametro_canale);
            } else {
                $template
                        ->assign('showFileInfo_editUri',
                                'v2.php?do=FileEdit&id_file='
                                        . $file->getIdFile()
                                        . $parametro_canale);
                $template
                        ->assign('showFileInfo_deleteUri',
                                'v2.php?do=FileDelete&id_file='
                                        . $file->getIdFile()
                                        . $parametro_canale);
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
            $template
                    ->assign('showFileInfo_addComment',
                            'v2.php?do=FileStudentiComment&id_file='
                                    . $file->getIdFile());
        }

        $canali_tpl = array();
        $id_canali = $file->getIdCanali();
        foreach ($id_canali as $id_canale) {
            $canale = Canale::retrieveCanale($id_canale);
            $canali_tpl[$id_canale] = array();
            $canali_tpl[$id_canale]['titolo'] = $canale->getTitolo();
            $canali_tpl[$id_canale]['uri'] = $canale->showMe();
        }

        $template
                ->assign('showFileInfo_downloadUri',
                        'v2.php?do=FileDownload&id_file='
                                . $file->getIdFile() . $parametro_canale);
        $template->assign('showFileInfo_langDelete', 'Elimina');
        $template->assign('showFileInfo_langDownload', 'Scarica');
        $template->assign('showFileInfo_langEdit', 'Modifica');
        $template
                ->assign('showFileInfo_uri',
                        'v2.php?do=showFileInfo&id_file='
                                . $file->getIdFile() . $parametro_canale);
        $template->assign('showFileInfo_titolo', $file->getTitolo());
        $template->assign('showFileInfo_descrizione', $file->getDescrizione());
        $template
                ->assign('showFileInfo_userLink',
                        'v2.php?do=ShowUser&id_utente='
                                . $file->getIdUtente());
        $template->assign('showFileInfo_username', $file->getUsername());
        $template
                ->assign('showFileInfo_dataInserimento',
                        $krono->k_date('%j/%m/%Y', $file->getDataInserimento()));
        $template
                ->assign('showFileInfo_new',
                        ($file->getDataModifica() < $user->getUltimoLogin()) ? 'true'
                                : 'false');
        $template->assign('showFileInfo_nomeFile', $nomeFile);
        $template->assign('showFileInfo_dimensione', $file->getDimensione());
        $template->assign('showFileInfo_download', $file->getDownload());
        $template->assign('showFileInfo_hash', $file->getHashFile());
        $template->assign('showFileInfo_categoria', $file->getCategoriaDesc());
        $template->assign('showFileInfo_tipo', $file->getTipoDesc());
        $template
                ->assign('showFileInfo_icona',
                        $fc->getAppSetting('filesTipoIconePath')
                                . $file->getTipoIcona());
        $template->assign('showFileInfo_info', $file->getTipoInfo());
        $template->assign('showFileInfo_canali', $canali_tpl);
        $template
                ->assign('showFileInfo_paroleChiave', $file->getParoleChiave());
        $template
                ->assign('isFileStudente',
                        (($tipo_file == true) ? 'true' : 'false'));
    }
}
