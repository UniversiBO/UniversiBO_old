<?php

use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;

require_once ('Files/FileItemStudenti'.PHP_EXTENSION);
require_once ('Commenti/CommentoItem'.PHP_EXTENSION);

/**
 * FileStudentiCommentEdit: Modifica un commento di un File Studente
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Fabrizio Pinto
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class FileStudentiCommentEdit extends UniversiboCommand {

	function execute() {
		
		$frontcontroller = & $this->getFrontController();
		$template = & $frontcontroller->getTemplateEngine();
				
		$krono = & $frontcontroller->getKrono();
		
		$user = & $this->getSessionUser();
		$user_ruoli = & $user->getRuoli();
		
		if (!array_key_exists('id_commento', $_GET) || !preg_match('/^([0-9]{1,9})$/', $_GET['id_commento']))
		{
			Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getIdUser(), 'msg' => 'L\'id del commento non è valido', 'file' => __FILE__, 'line' => __LINE__));
		}
		
		$id_commento = $_GET['id_commento'];
		$commentoItem = CommentoItem::selectCommentoItem($id_commento);
		$id_utente = $commentoItem->getIdUtente();
		$id_file_studente = $commentoItem->getIdFileStudente();
				
		$template->assign('common_canaleURI', array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER['HTTP_REFERER'] : '' );
		$template->assign('common_langCanaleNome', 'indietro');
		
		$referente = false;
		$moderatore = false;
		
		$autore = ($id_utente == $user->getIdUser());
		
		if (array_key_exists('id_canale', $_GET))
		{
			if (!preg_match('/^([0-9]{1,9})$/', $_GET['id_canale']))
				Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getIdUser(), 'msg' => 'L\'id del canale richiesto non è valido', 'file' => __FILE__, 'line' => __LINE__));

			$canale = & Canale::retrieveCanale($_GET['id_canale']);
			$id_canale = $_GET['id_canale'];
			if ($canale->getServizioFilesStudenti() == false) 
				Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getIdUser(), 'msg' => "Il servizio files studenti é disattivato", 'file' => __FILE__, 'line' => __LINE__));
		
			if (array_key_exists($id_canale, $user_ruoli)) {
				$ruolo = & $user_ruoli[$id_canale];
	
				$referente = $ruolo->isReferente();
				$moderatore = $ruolo->isModeratore();
			}
			//controllo coerenza parametri
			$file = FileItemStudenti::selectFileItem($id_file_studente);
			$canali_file	= 	$file->getIdCanali();
			
			//TO DO: perché non funziona il controllo???
			
//			var_dump($canali_file);
//			die();
//			if (!in_array($id_canale, $canali_file))
//				 Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getIdUser(), 'msg' => 'I parametri passati non sono coerenti', 'file' => __FILE__, 'line' => __LINE__));
			
			$elenco_canali = array($id_canale);
			
			//controllo diritti sul canale
			if (!($user->isAdmin() || $referente || $moderatore || $autore))
				Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getIdUser(), 'msg' => "Non hai i diritti per modificare il commento\n La sessione potrebbe essere scaduta", 'file' => __FILE__, 'line' => __LINE__));
				
		}
		elseif (!($user->isAdmin() || $autore)) 
				Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getIdUser(), 'msg' => "Non hai i diritti per modificare il commento\n La sessione potrebbe essere scaduta", 'file' => __FILE__, 'line' => __LINE__));		
		
		
		// valori default form
		// $f27_file = '';
		$f27_commento = $commentoItem->getCommento();
		$f27_voto = $commentoItem->getVoto();		

		$this->executePlugin('ShowFileStudentiCommento', array( 'id_commento' => $id_commento));

		$f27_accept = false;
		
		if (array_key_exists('f27_submit', $_POST)) 
		{
			$f27_accept = true;

			//commento
			if(trim($_POST['f27_commento']) == '')
			{
				Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Inserisci un commento', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
				$f27_accept = false;
			}
			else
			{
				$f27_commento = $_POST['f27_commento'];
			}
			
			//voto
			if (!ereg('^([0-5]{1})$', $_POST['f27_voto'])) {
				Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getIdUser(), 'msg' => 'Voto non valido', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
				$f27_accept = false;
			} else
				$f27_voto = $_POST['f27_voto'];
			
			
			
			//esecuzione operazioni accettazione del form
			if ($f27_accept == true) 
			{
				
				CommentoItem::updateCommentoItem($id_commento,$f27_commento,$f27_voto);
				$template->assign('common_canaleURI','index.php?do=FileShowInfo&id_file='.$id_file_studente.'&id_canale='.$id_canale);
				return 'success';
			}

		} 
		//end if (array_key_exists('f27_submit', $_POST))

		
		// resta da sistemare qui sotto, fare il form e fare debugging
		
		$template->assign('f27_commento', $f27_commento);
		$template->assign('f27_voto', $f27_voto);
		return 'default';

	}

}
