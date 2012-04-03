<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;

use UniversiBO\Bundle\LegacyBundle\App\Commenti\CommentoItem;

use UniversiBO\Bundle\LegacyBundle\App\Files\FileItem;

use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;
use UniversiBO\Bundle\LegacyBundle\App\Files\FileItemStudenti;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;


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
 	function execute()
 	{
 		$frontcontroller = $this->getFrontController();
		$template = $frontcontroller->getTemplateEngine();
		$user = $this->getSessionUser();	
		$arrayFilesStudenti = array();
		
		if (!array_key_exists('order', $_GET) || !preg_match('/^([0-9]{1,9})$/', $_GET['order'] ) || ($_GET['order'] > 2) )
		{
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $user->getIdUser(), 'msg'=>'L\'ordine richiesto non e` valido','file'=>__FILE__,'line'=>__LINE__ ));
		}
		$order = $_GET['order'];
		
		$arrayFilesStudenti = $this->getAllFiles($order);
		$this->executePlugin('ShowAllFilesStudentiTitoli', array('files'=>$arrayFilesStudenti,'chk_diritti'=>false));
		switch($order)
		{
			case 0:
				$template->assign('showAllFilesStudenti_titoloPagina','ordinati per nome');
				$template->assign('showAllFilesStudenti_url1','index.php?do=ShowAllFilesStudenti&order=1');
		    	$template->assign('showAllFilesStudenti_lang1','Mostra i Files Studenti ordinati per data di inserimento');
		    	$template->assign('showAllFilesStudenti_url2','index.php?do=ShowAllFilesStudenti&order=2');
		    	$template->assign('showAllFilesStudenti_lang2','Mostra i Files Studenti ordinati per voto medio');
		    	break;
		
			case 1:
				$template->assign('showAllFilesStudenti_titoloPagina','ordinati per data di inserimento');
				$template->assign('showAllFilesStudenti_url1','index.php?do=ShowAllFilesStudenti&order=0');
				$template->assign('showAllFilesStudenti_lang1','Mostra i Files Studenti ordinati per nome');
				$template->assign('showAllFilesStudenti_url2','index.php?do=ShowAllFilesStudenti&order=2');
		    	$template->assign('showAllFilesStudenti_lang2','Mostra i Files Studenti ordinati per voto medio');
				break;
			
			case 2:
				$template->assign('showAllFilesStudenti_titoloPagina','ordinati per voto medio');
				$template->assign('showAllFilesStudenti_url1','index.php?do=ShowAllFilesStudenti&order=0');
				$template->assign('showAllFilesStudenti_lang1','Mostra i Files Studenti ordinati per nome');
				$template->assign('showAllFilesStudenti_url2','index.php?do=ShowAllFilesStudenti&order=1');
		    	$template->assign('showAllFilesStudenti_lang2','Mostra i Files Studenti ordinati per data di inserimento');
				break;
		}
		
 	}
 	
 	function  getAllFiles($order)//c\`era $num
	{ 
		$quale_ordine = '';
		$group = '';
		
		switch($order)
		{
			case 0:
			    $quale_ordine = 'A.titolo';
				break;
		    case 1:
		    	$quale_ordine = 'A.data_inserimento DESC';
		    	break;
		    case 2:
		        $quale_ordine = 'avg(B.voto) DESC';
		        $group = 'GROUP BY A.id_file';
		        break;
		}
		$db = FrontController::getDbConnection('main');
		$query = 'SELECT A.id_file FROM file A, file_studente_commenti B' .
				 ' WHERE A.id_file = B.id_file and A.eliminato != '.$db->quote(FileItem::ELIMINATO).
				 ' AND B.eliminato != '.$db->quote(CommentoItem::ELIMINATO).
				 ''.$group.' ORDER BY '.$quale_ordine;
				 
		$res = $db->query($query);
		if (DB::isError($res)) 
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $this->sessionUser->getIdUser(), 'msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__)); 
	
		$rows = $res->numRows();

		if( $rows = 0) return false;
		
		$id_files_studenti_list = array();
	
		while ( $res->fetchInto($row) )
		{
			$id_files_studenti_list[]= $row[0];
		}
		
		$res->free();
		
		$files_studenti_list = FileItemStudenti::selectFileItems($id_files_studenti_list);
		return $files_studenti_list;
		
	}
 }
