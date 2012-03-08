<?php

require_once ('UniversiboCommand'.PHP_EXTENSION);
require_once ('ProgrammazioneDidattica/ProgrammazioneDidatticaDataRetrieverFactory'.PHP_EXTENSION);
require_once ('Facolta'.PHP_EXTENSION);
require_once ('Cdl'.PHP_EXTENSION);


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
 
class ProgrammazioneDidatticaCdl extends UniversiboCommand 
{
	function execute()
	{
		$fc = $this->getFrontController();
		$template = $this->frontController->getTemplateEngine();
		
		$session_user = $this->getSessionUser();
		
		if (!arraY_key_exists('cod_fac',$_GET) || !ereg('^([0-9A-Z]{4})$', $_GET['cod_fac']))
				Error::throwError(_ERROR_DEFAULT, array ('id_utente' => $session_user->getIdUser(), 'msg' => 'L\'id della facolta\' richiesta non e\' valido', 'file' => __FILE__, 'line' => __LINE__));
		$codFac = $_GET['cod_fac'];
		
		if (!$session_user->isAdmin())
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $session_user->getIdUser(), 'msg'=>'La gestione della didattica e\' accessibile solo ad utenti amministratori.'."\n".'La sessione potrebbe essere scaduta, eseguire il login','file'=>__FILE__,'line'=>__LINE__));
				
		$username_allowed = explode(';',$fc->getAppSetting('programmazioneDidatticaAdmin'));
		if (!in_array($session_user->getUsername(), $username_allowed ))
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $session_user->getIdUser(), 'msg'=>'La gestione della didattica e\' accessibile solo a '.implode(', ',$username_allowed),'file'=>__FILE__,'line'=>__LINE__));
 		
 		if ( array_key_exists('f25_submit_publish', $_POST) || array_key_exists('f25_submit_hide', $_POST))
		{
			if (array_key_exists('f25_submit_publish', $_POST))
			{
				$keys = array_keys($_POST['f25_submit_publish']);
				$permessi = USER_ALL;
			}	
			else 
			{
				$keys = array_keys($_POST['f25_submit_hide']);
				$permessi = USER_NONE;
			}	
			
			if (count($keys) == 0) 
				Error::throwError(_ERROR_DEFAULT,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Il form inviato non e\' valido','file'=>__FILE__,'line'=>__LINE__));
			$cod_cdl = $keys[0];
				
			if (!ereg('^([0-9A-Z]{4})$', $cod_cdl))
				Error::throwError(_ERROR_DEFAULT, array ('id_utente' => $session_user->getIdUser(), 'msg' => 'L\'id della facolta\' richiesta non e\' valido', 'file' => __FILE__, 'line' => __LINE__));
			
			$cdl = Cdl::selectCdlCodice($cod_cdl);
			
			$cdl->setPermessi($permessi);
			$cdl->updateCdl();
		}
		
				
		
		$data_retriever = ProgrammazioneDidatticaDataRetrieverFactory::getProgrammazioneDidatticaDataRetriever("web_service");
		$elenco_cdl = $data_retriever->getCorsoListFacolta($codFac);
		$num_cdl = count($elenco_cdl);
		
		
		$keys_nuovi_cod_cdl = array();
		if ( array_key_exists('f25_submit', $_POST) )
		{
			$keys_nuovi_cod_cdl = array_keys($_POST['f25_cod_corso']);
			for($i=0; $i<count($keys_nuovi_cod_cdl);  $i++)
			{
				if (!ereg('^([0-9A-Z]{4})$', $keys_nuovi_cod_cdl[$i]))
					Error::throwError(_ERROR_DEFAULT, array ('id_utente' => $session_user->getIdUser(), 'msg' => 'L\'id del corso da aggiungere non e\' valido', 'file' => __FILE__, 'line' => __LINE__));
				}
		}
		
		
		$tpl_elenco_cdl = array();
		
		for($i=0; $i<$num_cdl;  $i++)
		{
			$cdl_db = Cdl::selectCdlCodice($elenco_cdl[$i]->codCorso);
			
			if ($cdl_db === false)
			{ 
				$cdl_attiva = 'false';
				$public = 'false';
				
				if (in_array($elenco_cdl[$i]->codCorso,$keys_nuovi_cod_cdl))
				{
					$presidente =  ($elenco_cdl[$i]->codDocPresidente == '000000') ? null : $elenco_cdl[$i]->codDocPresidente ;
					
					$new_cdl = new Cdl(0, USER_NONE, time(), CANALE_CDL, '', '', 0,
												true, false, false, null, null, true, false,
												$elenco_cdl[$i]->codCorso, $elenco_cdl[$i]->descCorso, 
												Cdl::translateCategoriaCdl($elenco_cdl[$i]->tipoCorso), 
												$codFac, $elenco_cdl[$i]->codDocPresidente , null);

					if ($new_cdl->insertCdl())
					{
						$cdl_attiva = 'true';
						$public = 'false';
					}
					
				}

			}	
			else
			{
				$cdl_attiva = 'true';
				if ($cdl_db->getPermessi() == USER_NONE)
					$public = 'false';
				else
					$public = 'true';
			}
			
			$tpl_elenco_cdl[] = array("desc_corso" => $elenco_cdl[$i]->descCorso, "cod_corso" => $elenco_cdl[$i]->codCorso, "cod_doc_presidente" => $elenco_cdl[$i]->codDocPresidente, "attiva" => $cdl_attiva, "public" => $public );
		}
		
		$template->assign('programmazioneDidatticaCdl_elencoCdl',$tpl_elenco_cdl);
		
		return 'default';
		
	}
}

?>
