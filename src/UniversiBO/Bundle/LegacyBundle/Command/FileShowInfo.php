<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;
use \Error;
use UniversiBO\Bundle\LegacyBundle\App\Files\FileItemStudenti;
use UniversiBO\Bundle\LegacyBundle\App\Files\FileItem;
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * FileShowInfo: mostra tutte le informazioni correlate ad un file
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class FileShowInfo extends UniversiboCommand
{

    function execute()
    {
        $frontcontroller = &$this->getFrontController();

        $template = &$frontcontroller->getTemplateEngine();
        $krono = &$frontcontroller->getKrono();
        $user = $this->getSessionUser();

        if (!array_key_exists('id_file', $_GET)
                || !preg_match('/^([0-9]{1,9})$/', $_GET['id_file'])) {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => 'L\'id del file richiesto non � valido',
                            'file' => __FILE__, 'line' => __LINE__));
        }
        $id_file = $_GET['id_file'];
        $tipo_file = FileItemStudenti::isFileStudenti($id_file);
        //
        //		$file = FileItem::selectFileItem($_GET['id_file']);
        //
        //        $directoryFile = $frontcontroller->getAppSetting('filesPath');
        //		$nomeFile = $file->getIdFile().'_'.$file->getNomeFile();
        //
        //		if (!$user->isGroupAllowed( $file->getPermessiVisualizza() ) )
        //			Error :: throwError(_ERROR_DEFAULT, array ('msg' => 'Non ? permesso visualizzare il file.
        //			Non possiedi i diritti necessari, la sessione potrebbe essere scaduta.', 'file' => __FILE__, 'line' => __LINE__, 'log' => true));
        //
        //
        //		if (($user->isAdmin() || $user->getIdUser() == $file->getIdUser() ))
        //		{
        //			$file_tpl['modifica']     = 'Modifica';
        //			$file_tpl['modifica_link']= 'v2.php?do=FileEdit&id_file='.$file->getIdFile();
        //			$file_tpl['elimina']      = 'Elimina';
        //			$file_tpl['elimina_link'] = 'v2.php?do=FileDelete&id_file='.$file->getIdFile();
        //		}
        //
        //		$id_canali = $file->getIdCanali();
        //		foreach($id_canali as $id_canale)
        //		{
        //			$canale = Canale::retrieveCanale($id_canale);
        //			$canali_tpl[$id_canale] = array();
        //			$canali_tpl[$id_canale]['titolo'] = $canale->getTitolo();
        //			$canali_tpl[$id_canale]['uri'] = $canale->showMe();
        //		}
        //
        //		$template->assign('fileShowInfo_downloadUri', 'v2.php?do=FileDownload&id_file='.$file->getIdFile());
        //		$template->assign('fileShowInfo_uri', 'v2.php?do=FileShowInfo&id_file='.$file->getIdFile());
        //		$template->assign('fileShowInfo_titolo', $file->getTitolo());
        //		$template->assign('fileShowInfo_descrizione', $file->getDescrizione());
        //		$template->assign('fileShowInfo_userLink', 'ShowUser&id_utente='.$file->getIdUtente());
        //		$template->assign('fileShowInfo_username', $file->getUsername());
        //		$template->assign('fileShowInfo_dataInserimento', $krono->k_date('%j/%m/%Y', $file->getDataInserimento()));
        //		$template->assign('fileShowInfo_new', ($file->getDataModifica() < $user->getUltimoLogin() ) ? 'true' : 'false' );
        //		$template->assign('fileShowInfo_nomeFile', $nomeFile);
        //		$template->assign('fileShowInfo_dimensione',  $file->getDimensione());
        //		$template->assign('fileShowInfo_download',  $file->getDownload());
        //		$template->assign('fileShowInfo_hash',  $file->getHashFile());
        //		$template->assign('fileShowInfo_categoria', $file->getCategoriaDesc());
        //		$template->assign('fileShowInfo_tipo', $file->getTipoDesc());
        //		$template->assign('fileShowInfo_icona', $frontcontroller->getAppSetting('filesTipoIconePath').$file->getTipoIcona());
        //		$template->assign('fileShowInfo_info', $file->getTipoInfo());
        //		$template->assign('fileShowInfo_canali', $canali_tpl);
        //		$template->assign('fileShowInfo_paroleChiave', $file->getParoleChiave());
        //
        if (array_key_exists('id_canale', $_GET)
                && preg_match('/^([0-9]{1,9})$/', $_GET['id_canale']))
            $this
                    ->executePlugin('ShowFileInfo',
                            array('id_file' => $_GET['id_file'],
                                    'id_canale' => $_GET['id_canale']));
        else
            $this
                    ->executePlugin('ShowFileInfo',
                            array('id_file' => $_GET['id_file']));
        if ($tipo_file == true) {
            $template->assign('isFileStudente', 'true');
            $this
                    ->executePlugin('ShowFileStudentiCommenti',
                            array('id_file' => $id_file));
        } else
            $template->assign('isFileStudente', 'false');

        return;

    }
}
