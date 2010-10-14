<?php

require_once ('UniversiboCommand'.PHP_EXTENSION);
require_once ('ForumApi'.PHP_EXTENSION);


/**
 * NewPasswordStudente is an extension of UniversiboCommand class.
 *
 * Si occupa della generazione di una nuova password per gli utenti che l'hanno smarrita
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
 
class NewPasswordStudente extends UniversiboCommand 
{
	
	function execute()
	{
		$fc =& $this->getFrontController();
		$template =& $this->frontController->getTemplateEngine();
		$user =& $this->getSessionUser();
		if (!$user->isOspite())
		{
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $user->getIdUser(), 'msg'=>'L\'iscrizione può essere richiesta solo da utenti che non hanno ancora eseguito l\'accesso','file'=>__FILE__,'line'=>__LINE__));
		}

		$template->assign('newPasswordStudente_langNewPasswordAlt','Recupera Password');
		$template->assign('newPasswordStudente_langMail','e-mail di ateneo:');
		$template->assign('newPasswordStudente_langPassword','Password:');
		$template->assign('newPasswordStudente_langUsername','Username:');
		$template->assign('newPasswordStudente_domain','@studio.unibo.it');
		$template->assign('newPasswordStudente_langInfoNewPassword','Gli studenti che hanno smarrito la password di accesso ad UniversiBO possono ottenerne una nuova, ricevendola all\'e-mail di ateneo utilizzata al momento dell\'iscrizione.'."\n".
							'Per problemi indipendenti da noi [b]la casella e-mail verrà creata nelle 24 ore successive[/b] all\'iscrizione al portale [url]http://www.unibo.it[/url] e potete accedervi tramite il sito [url]https://posta.studio.unibo.it[/url], vi preghiamo di apettare che la mail di ateneo sia attiva prima di richiedere una nuova password.');
		$template->assign('newPasswordStudente_langHelp','Per qualsiasi problema o spiegazioni contattate lo staff all\'indirizzo [email]'.$fc->getAppSetting('infoEmail').'[/email].'."\n".
							'In ogni caso non comunicate mai le vostre password di ateneo, lo staff non è tenuto a conoscerle');
		$template->assign('f5_submit',		'Invia');
							

		// valori default form
		$f5_username =	'';
		$f5_password =	'';
		$f5_ad_user =	'';
		
		$f5_accept = false;
		
		if ( array_key_exists('f5_submit', $_POST)  )
		{
			$f5_accept = true;

			//var_dump($_POST);
			if ( !array_key_exists('f5_username', $_POST) ||
				 !array_key_exists('f5_password', $_POST) ||
				 !array_key_exists('f5_ad_user', $_POST) ) 
			{
				Error::throwError(_ERROR_DEFAULT,array('id_utente' => $user->getIdUser(), 'msg'=>'Il form inviato non è valido','file'=>__FILE__,'line'=>__LINE__ ));
				$f5_accept = false;
			}
			
			//ad_user
			if ( $_POST['f5_ad_user'] == '' ) {
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Inserire la e-mail di ateneo','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f5_accept = false;
			}
			elseif ( strlen($_POST['f5_ad_user']) > 30 ) {
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Lo username di ateneo indicato può essere massimo 30 caratteri','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f5_accept = false;
			}
			elseif(ereg('@studio\.unibo\.it$',$_POST['f5_ad_user'])){
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Non inserire il suffisso "@studio.unibo.it" nella email di ateneo','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f5_accept = false;
			}
			elseif(!eregi('^([[:alnum:]])+\.[[[:alnum:]]+$',$_POST['f5_ad_user'])){
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'La mail di ateneo inserita '.$_POST['f5_ad_user'].' non è sintatticamente valida','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f5_accept = false;
			}
			elseif(!User::activeDirectoryUsernameExists($_POST['f5_ad_user'].'@studio.unibo.it')){
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Non esiste alcun utente di Universibo registrato con la mail di ateneo '.$_POST['f5_ad_user'].'@studio.unibo.it'.' ','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f5_accept = false;
			}
			else{
				$f5_ad_user = strtolower($_POST['f5_ad_user']);
				$q5_ad_user = strtolower($f5_ad_user.'@studio.unibo.it');
			}
			
			//password
			if ( $_POST['f5_password'] == '' ) {
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Inserire la password della e-mail di ateneo','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f5_accept = false;
			}
			elseif ( strlen($_POST['f5_password']) > 50 ){
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'La lunghezza massima della password accettata dal sistema è di massimo 50 caratteri','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f5_accept = false;
			}
			else $q5_password = $f5_password = $_POST['f5_password'];
			
			//username
			if ( $_POST['f5_username'] == '' ) {
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Inserire il proprio username','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f5_accept = false;
			}
			elseif ( !User::isUsernameValid( $_POST['f5_username'] ) ){
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Nello username sono permessi fino a 25 caratteri alfanumerici con lettere accentate, spazi, punti, underscore','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f5_accept = false;
			}
			elseif ( !User::usernameExists( $_POST['f5_username'] ) ){
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Lo username richiesto non è registrato da nessun utente','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f5_accept = false;
			}
			else $q5_username = $f5_username = $_POST['f5_username'];
			
		}

		if ( $f5_accept == true )
		{
		
			//controllo active directory
			$adl_host = $fc->getAppSetting('adLoginHost');
			$adl_port = $fc->getAppSetting('adLoginPort'); 
			if (! User::activeDirectoryLogin($f5_ad_user, 'studio.unibo.it', $q5_password, $adl_host, $adl_port ) )
			{
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'L\'autenticazione tramite e-mail di ateneo ha fornito risultato negativo','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				return 'default';
			}
			
			//controllo corrispondenza usarname-usernamen di ateneo
			$user =& User::selectUserUsername($q5_username);
			
			if ( $user->isEliminato() )
			{
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Lo username inserito non è registrato da nessun utente','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				return 'default';
			}
			
			
			if ( $user->getADUsername() != $q5_ad_user )
			{
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Lo username inserito non corrisponde con la mail di ateneo precedentemente registrata','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				return 'default';
			}
			
			//azioni
			$randomPassword = User::generateRandomPassword();
			
			if ($user->updatePasswordHash(User::passwordHashFunction($randomPassword),true) == false)
				Error::throwError(_ERROR_DEFAULT,array('id_utente' => $user->getIdUser(), 'msg'=>'Si è verificato un errore durante l\'aggiornamento della password relativa allo username '.$q5_username.' mail '.$q5_ad_user,'file'=>__FILE__,'line'=>__LINE__));

			$forum = new ForumApi();
			$forum->updatePasswordHash($user);
			//	Error::throwError(_ERROR_DEFAULT,'msg'=>'Si è verificato un errore durente la registrazione dell\'account username '.$q5_username.' mail '.$q5_ad_user,'file'=>__FILE__,'line'=>__LINE__));
			
			
			$mail =& $fc->getMail();

			$mail->AddAddress($user->getADUsername());

			$mail->Subject = "Registrazione UniversiBO";
			$mail->Body = "Ciao \"".$user->getUsername()."\"\nE' stata richiesta la generazione di una nuova password per permetterti l'accesso ad UniversiBO\n\n".
				"Per accedere al sito utilizza l'indirizzo ". $fc->getAppSetting('rootUrl') ."\n\n".
				"Le informazioni per permetterti l'accesso ai servizi offerti dal portale sono:\n".
				"Username: ".$user->getUsername()."\n".
				"Password: ".$randomPassword."\n\n".
				"Questa password e' stata generata in modo casuale, sul sito  e' disponibile attraverso la pagina di Impostazioni Personali la funzionalita' per poterla cambiare a tuo piacimento\n\n".
				"Qualora avessi ricevuto questa e-mail per errore, segnalalo rispondendo a questo messaggio";
			
			
			$msg = "La nuova password è stata registrata con successo ma non è stato possibile inviarti la password tramite e-mail\n".
				"Le informazioni per permetterti l'accesso ai servizi offerti da UniversiBO sono:\n".
				"Username: ".$user->getUsername()."\n".
				"Password: ".$randomPassword."\n\n";
			
			if(!$mail->Send())
				Error::throwError(_ERROR_DEFAULT,array('id_utente' => $user->getIdUser(), 'msg'=>$msg, 'file'=>__FILE__, 'line'=>__LINE__));
			
			$template->assign('newPasswordStudente_thanks',"Una nuova password è stata generata, la tua richiesta è stata inoltrata e a breve riceverai le informazioni al tuo indirizzo e-mail di ateneo\n".
								'Per qualsiasi problema o spiegazioni contatta lo staff all\'indirizzo [email]'.$fc->getAppSetting('infoEmail').'[/email].');
			
			//elimino la password
			$randomPassword = '';
			$mail->Body = '';
			$msg = '';
			
			return 'success';
			
		}
		
		// riassegna valori form
		$template->assign('f5_username',	$f5_username);
		$template->assign('f5_password',	'');
		$template->assign('f5_ad_user',		$f5_ad_user);

		return 'default';
		
	}
}

?>