<?php
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * RecuperaUsernameStudente is an extension of UniversiboCommand class.
 *
 * Visualizza l'username all'utente che lo ha smarrito o dimenticato
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class RecuperaUsernameStudente extends UniversiboCommand 
{
	
	function execute()
	{
		$fc = $this->getFrontController();
		$template = $fc->getTemplateEngine();
		$user = $this->getSessionUser();
		
		if (!$user->isOspite())
		{
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $user->getIdUser(), 'msg'=>'Il recupero dell\'username può essere richiesto solo da utenti che non hanno ancora eseguito l\'accesso','file'=>__FILE__,'line'=>__LINE__));
		}

		$template->assign('recuperaUsernameStudente_langNewPasswordAlt','Recupera Username');
		$template->assign('recuperaUsernameStudente_langMail','e-mail di ateneo:');
		$template->assign('recuperaUsernameStudente_langPassword','Password:');
		$template->assign('recuperaUsernameStudente_domain','@studio.unibo.it');
		$template->assign('recuperaUsernameStudente_langInfo','Gli studenti che hanno smarrito la password di accesso ad UniversiBO possono ottenerne una nuova, ricevendola all\'e-mail di ateneo utilizzata al momento dell\'iscrizione.');
		$template->assign('recuperaUsernameStudente_langHelp','Per qualsiasi problema o spiegazioni contattate lo staff all\'indirizzo [email]'.$fc->getAppSetting('infoEmail').'[/email].'."\n".
							'In ogni caso non comunicate mai le vostre password di ateneo, lo staff non è tenuto a conoscerle');
		$template->assign('f32_submit',		'Invia');			

		// valori default form
		$f32_password =	'';
		$f32_ad_user =	'';
		
		$f32_accept = false;
		
		if ( array_key_exists('f32_submit', $_POST)  )
		{
			$f32_accept = true;

			//var_dump($_POST);
			if ( !array_key_exists('f32_password', $_POST) ||
				 !array_key_exists('f32_ad_user', $_POST) ) 
			{
				Error::throwError(_ERROR_DEFAULT,array('id_utente' => $user->getIdUser(), 'msg'=>'Il form inviato non è valido','file'=>__FILE__,'line'=>__LINE__ ));
				$f32_accept = false;
			}
			
			//ad_user
			if ( $_POST['f32_ad_user'] == '' ) {
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Inserire la e-mail di ateneo','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f32_accept = false;
			}
			elseif ( strlen($_POST['f32_ad_user']) > 30 ) {
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Lo username di ateneo indicato può essere massimo 30 caratteri','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f32_accept = false;
			}
			elseif(ereg('@studio\.unibo\.it$',$_POST['f32_ad_user'])){
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Non inserire il suffisso "@studio.unibo.it" nella email di ateneo','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f32_accept = false;
			}
			elseif(!eregi('^([[:alnum:]])+\.[[[:alnum:]]+$',$_POST['f32_ad_user'])){
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'La mail di ateneo inserita '.$_POST['f32_ad_user'].' non è sintatticamente valida','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f32_accept = false;
			}
			elseif(!User::activeDirectoryUsernameExists($_POST['f32_ad_user'].'@studio.unibo.it')){
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Non esiste alcun utente di Universibo registrato con la mail di ateneo '.$_POST['f32_ad_user'].'@studio.unibo.it'.' ','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f32_accept = false;
			}
			else{
				$f32_ad_user = strtolower($_POST['f32_ad_user']);
				$q32_ad_user = strtolower($f32_ad_user.'@studio.unibo.it');
			}
			
			//password
			if ( $_POST['f32_password'] == '' ) {
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Inserire la password della e-mail di ateneo','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f32_accept = false;
			}
			elseif ( strlen($_POST['f32_password']) > 50 ){
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'La lunghezza massima della password accettata dal sistema è di massimo 50 caratteri','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f32_accept = false;
			}
			else $q32_password = $f32_password = $_POST['f32_password'];
						
		}

		if ( $f32_accept == true )
		{
			//controllo active directory
			$adl_host = $fc->getAppSetting('adLoginHost');
			$adl_port = $fc->getAppSetting('adLoginPort'); 
			if (! User::activeDirectoryLogin($f32_ad_user, 'studio.unibo.it', $q32_password, $adl_host, $adl_port ) )
			{
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'L\'autenticazione tramite e-mail di ateneo ha fornito risultato negativo','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				return 'default';
			}
					
			//azioni
			$id_utente = User::getIdFromADUsername($q32_ad_user);
			$username = User::getUsernameFromId($id_utente);
			$user = User::selectUser($id_utente);						
			if ( $user->isEliminato())
			{
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Non esiste alcun utente di Universibo registrato con la mail di ateneo fornita','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				return 'default';
			}
			$mail = $fc->getMail();

			$mail->AddAddress($q32_ad_user);

			$mail->Subject = "Registrazione UniversiBO";
			$mail->Body = "Ciao. \nE' stata richiesto il recupero dell'username per l'accesso ad UniversiBO\n\n".
				"Per accedere al sito utilizza l'indirizzo ". $fc->getAppSetting('rootUrl') ."\n\n".
				"Le informazioni per permetterti l'accesso ai servizi offerti dal portale UniversiBO sono:\n".
				"Username: ".$username."\n".
				"\nNel caso avessi dimenticato anche la password, ti invitiamo a ottenerne una nuova tramite il link 'password smarrita'".
				" che trovi soot i campi per il login\n\n".
				"Qualora avessi ricevuto questa e-mail per errore, segnalalo rispondendo a questo messaggio";
			
			
			$msg = "Non è stato possibile inviarti lo username tramite e-mail\n".
				"Le informazioni per permetterti l'accesso ai servizi offerti da UniversiBO sono:\n".
				"Username: ".$username."\n";
			
			if(!$mail->Send())
				Error::throwError(_ERROR_DEFAULT,array('id_utente' => $user->getIdUser(), 'msg'=>$msg, 'file'=>__FILE__, 'line'=>__LINE__));
			
			$template->assign('recuperaUsernameStudente_thanks',"La tua richiesta è stata inoltrata e a breve riceverai le informazioni al tuo indirizzo e-mail di ateneo\n".
								'Per qualsiasi problema o spiegazioni contatta lo staff all\'indirizzo [email]'.$fc->getAppSetting('infoEmail').'[/email].');
			
			$mail->Body = '';
			$msg = '';
			
			return 'success';
			
		}
		
		// riassegna valori form
		$template->assign('f32_password', '');
		$template->assign('f32_ad_user',  $f32_ad_user);

		return 'default';
		
	}
}
