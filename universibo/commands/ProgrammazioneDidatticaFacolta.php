<?php

require_once ('UniversiboCommand'.PHP_EXTENSION);
require_once ('ProgrammazioneDidattica/ProgrammazioneDidatticaDataRetrieverFactory'.PHP_EXTENSION);
//require_once ('ProgrammazioneDidattica/ProgrammazioneDidatticaAddDocente'.PHP_EXTENSION);
require_once ('Facolta'.PHP_EXTENSION);


/**
 * ChangePassword is an extension of UniversiboCommand class.
 *
 * Si occupa della modifica della password di un utente
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
 
class ProgrammazioneDidatticaFacolta extends UniversiboCommand 
{
	function execute()
	{
		$fc = $this->getFrontController();
		$template = $this->frontController->getTemplateEngine();
		
		$session_user = $this->getSessionUser();
		
		if (!$session_user->isAdmin())
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $session_user->getIdUser(), 'msg'=>'La gestione della didattica e\' accessibile solo ad utenti amministratori.'."\n".'La sessione potrebbe essere scaduta, eseguire il login','file'=>__FILE__,'line'=>__LINE__));
				
		$username_allowed = explode(';',$fc->getAppSetting('programmazioneDidatticaAdmin'));
		if (!in_array($session_user->getUsername(), $username_allowed ))
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $session_user->getIdUser(), 'msg'=>'La gestione della didattica e\' accessibile solo a '.implode(', ',$username_allowed),'file'=>__FILE__,'line'=>__LINE__));
		
		if ( array_key_exists('f24_submit_publish', $_POST) || array_key_exists('f24_submit_hide', $_POST))
		{
			if (array_key_exists('f24_submit_publish', $_POST))
			{
				$keys = array_keys($_POST['f24_submit_publish']);
				$permessi = USER_ALL;
			}	
			else 
			{
				$keys = array_keys($_POST['f24_submit_hide']);
				$permessi = USER_NONE;
			}	
			
			if (count($keys) == 0) 
				Error::throwError(_ERROR_DEFAULT,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Il form inviato non e\' valido','file'=>__FILE__,'line'=>__LINE__));
			$cod_fac = $keys[0];
				
			if (!ereg('^([0-9A-Z]{4})$', $cod_fac))
				Error::throwError(_ERROR_DEFAULT, array ('id_utente' => $session_user->getIdUser(), 'msg' => 'L\'id della facolta\' richiesta non e\' valido', 'file' => __FILE__, 'line' => __LINE__));
			
			$facolta = Facolta::selectFacoltaCodice($cod_fac);
			
			$facolta->setPermessi($permessi);
			$facolta->updateFacolta();
		}
		
		
		
		$data_retriever = ProgrammazioneDidatticaDataRetrieverFactory::getProgrammazioneDidatticaDataRetriever("web_service");
		$elenco_facolta = $data_retriever->getFacoltaList();
		$num_facolta = count($elenco_facolta);
		
		
		$keys_nuovi_cod_fac = array();
		if ( array_key_exists('f24_submit', $_POST) )
		{
			$keys_nuovi_cod_fac = array_keys($_POST['f24_cod_fac']);
			for($i=0; $i<count($keys_nuovi_cod_fac);  $i++)
			{
				if (!ereg('^([0-9A-Z]{4})$', $keys_nuovi_cod_fac[$i]))
					Error::throwError(_ERROR_DEFAULT, array ('id_utente' => $session_user->getIdUser(), 'msg' => 'L\'id della facolta\' da aggiungere non e\' valido', 'file' => __FILE__, 'line' => __LINE__));
				}
		}
		
		
		$tpl_elenco_facolta = array();
		
		for($i=0; $i<$num_facolta;  $i++)
		{
			$facolta_db = Facolta::selectFacoltaCodice($elenco_facolta[$i]->codFac);
			
			if ($facolta_db === false)
			{ 
				$facolta_attiva = 'false';
				$public = 'false';
				
				if (in_array($elenco_facolta[$i]->codFac,$keys_nuovi_cod_fac))
				{
					$new_facolta = new Facolta(0, USER_NONE, time(), CANALE_FACOLTA, '', '', 0,
												true, false, false, null, null, true, false,
												$elenco_facolta[$i]->codFac, $elenco_facolta[$i]->descFac, '');
												/** @todo Facolta ancora non gestisce il Preside */
					
					$result = $new_facolta->insertFacolta();
//	@todo			if ( $elenco_facolta[$i]->codDoc != '000000' )
//						ProgrammazioneDidatticaAddDocente::addDocente($elenco_facolta[$i]->codDocPreside);
					
					
					if ($result == true)
					{
						$facolta_attiva = 'true';
						$public = 'false';
					}
					
				}

			}	
			else
			{
				$facolta_attiva = 'true';
				if ($facolta_db->getPermessi() == USER_NONE)
					$public = 'false';
				else
					$public = 'true';
			}
			
			$tpl_elenco_facolta[] = array("desc_fac" => $elenco_facolta[$i]->descFac, "cod_fac" => $elenco_facolta[$i]->codFac, "cod_doc_preside" => $elenco_facolta[$i]->codDocPreside, "attiva" => $facolta_attiva, "public" => $public );
		}
		
		$template->assign('programmazioneDidatticaFacolta_elencoFacolta',$tpl_elenco_facolta);
		
		return 'default';
		
	}
}

?>
