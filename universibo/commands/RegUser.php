<?php
use UniversiBO\Legacy\App\UniversiboCommand;
use UniversiBO\Legacy\App\User;

/**
 * RegStudente is an extension of UniversiboCommand class.
 *
 * Si occupa della registrazione degli studenti
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
 
class RegUser extends UniversiboCommand
{
	function execute()
	{
		$fc = $this->getFrontController();
		$template = $this->frontController->getTemplateEngine();
		
		$session_user = $this->getSessionUser();
		if (!$session_user->isAdmin())
		{
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $session_user->getIdUser(), 'msg'=>'L\'iscrizione manuale di nuovi utenti può essere effettuata solo da utenti Admin','file'=>__FILE__,'line'=>__LINE__));
		}
		
		$template->assign('f34_submit',		'Registra');
		$template->assign('regStudente_langRegAlt','Registrazione');

		
		// valori default form
		$f34_username =	'';
		$f34_email =	'';
		$f34_livello =	0;
		
		$f34_accept = false;
		
		if ( array_key_exists('f34_submit', $_POST)  )
		{
			$f34_accept = true;
			//var_dump($_POST);
			if ( !array_key_exists('f34_username', $_POST) ||
				 !array_key_exists('f34_email', $_POST) ||
				 !array_key_exists('f34_livello', $_POST) ) 
			{
				Error::throwError(_ERROR_DEFAULT,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Il form inviato non è valido','file'=>__FILE__,'line'=>__LINE__ ));
				$f34_accept = false;
			}
			
			//ad_user
			if ( $_POST['f34_email'] == '' ) {
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Inserire la e-mail','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f34_accept = false;
			}
			elseif(!eregi('^([[:alnum:]_\-])+(\.([[:alnum:]_\-])+)*@([[:alnum:]_\-])+(\.([[:alnum:]_\-])+)*$',$_POST['f34_email'])){
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'La mail di ateneo inserita '.$_POST['f34_email'].' non è sintatticamente valida','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f34_accept = false;
			}
			elseif(User::activeDirectoryUsernameExists($_POST['f34_email'])){
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'La mail '.$_POST['f34_email'].' appartiene ad un utente già registrato','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f34_accept = false;
			}
			else
			{
				$f34_email = strtolower($_POST['f34_email']);
				$q34_email = strtolower($f34_email);
			}
			
			
			//username
			if ( $_POST['f34_username'] == '' ) {
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Scegliere uno username','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f34_accept = false;
			}
			elseif($_POST['f34_username']{0}==' ' || $_POST['f34_username']{strlen($_POST['f34_username']) - 1}==' '){
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Non sono accettati spazi all\' inizio o alla fine dello username','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f34_accept = false;
			}
			elseif ( !User::isUsernameValid( $_POST['f34_username'] ) ){
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Nello username sono permessi fino a 25 caratteri alfanumerici con lettere accentate, spazi, punti, underscore','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f34_accept = false;
			}
			elseif ( User::usernameExists( $_POST['f34_username'] ) ){
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Lo username richiesto è già stato registrato da un altro utente','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f34_accept = false;
			}
			else $q34_username = $f34_username = $_POST['f34_username'];
			
			//livello
			if ( $_POST['f34_livello'] == '' ) {
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Il livello inserito è vuoto','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f34_accept = false;
			}
//			elseif ( $_POST['f34_livello'] != User::STUDENTE &&
//					 $_POST['f34_livello'] != User::COLLABORATORE &&
//					 $_POST['f34_livello'] != User::TUTOR &&
//					 $_POST['f34_livello'] != User::DOCENTE &&
//					 $_POST['f34_livello'] != User::ADMIN &&
//					 $_POST['f34_livello'] != User::PERSONALE ) 
//			{
			elseif ( $_POST['f34_livello'] != User::STUDENTE &&
					 $_POST['f34_livello'] != User::COLLABORATORE &&
					 $_POST['f34_livello'] != User::TUTOR &&
					 $_POST['f34_livello'] != User::ADMIN &&
					 $_POST['f34_livello'] != User::PERSONALE ) 
			{
				Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Il livello inserito non è tra quelli ammissibili','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
				$f34_accept = false;
			}
			else $q34_livello = $f34_livello = $_POST['f34_livello'];
			
		}

		if ( $f34_accept == true )
		{
			//controllo active directory
			$randomPassword = User::generateRandomPassword();
			$notifica = ($q34_livello == User::STUDENTE || $q34_livello == User::COLLABORATORE || $q34_livello == User::ADMIN || $q34_livello == User::TUTOR ) ? NOTIFICA_ALL : NOTIFICA_NONE;
			
			$new_user = new User(-1, $q34_livello, $q34_username ,$randomPassword, $q34_email, $notifica, 0, '', '', $fc->getAppSetting('defaultStyle') );
			
			if ($new_user->insertUser() == false)
				Error::throwError(_ERROR_DEFAULT,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Si è verificato un errore durente la registrazione dell\'account username '.$q34_username.' mail '.$q34_email,'file'=>__FILE__,'line'=>__LINE__));

			$forum = new ForumApi();
			$forum->insertUser($new_user);
			//	Error::throwError(_ERROR_DEFAULT,'msg'=>'Si è verificato un errore durente la registrazione dell\'account username '.$q34_username.' mail '.$q34_email,'file'=>__FILE__,'line'=>__LINE__));
			
			$mail = $fc->getMail();

			$mail->AddAddress($new_user->getEmail());

			$mail->Subject = "Registrazione UniversiBO";
			$mail->Body = "Benvenuto \"".$new_user->getUsername()."\"!!\nCi E' stata inoltrata una richiesta di iscrizione al sito UniversiBO\n\n".
			     "Per accedere al sito utilizza l'indirizzo ".$fc->getAppSetting('rootUrl')."\n\n".
				 "Le informazioni per permetterti l'accesso ai servizi offerti sono:\n".
				 "Username: ".$new_user->getUsername()."\n".
				 "Password: ".$randomPassword."\n\n".
				 "Questa password e' stata generata in modo casuale: sul sito  e' disponibile nella pagina delle tue impostazioni personali la funzionalita' per poterla cambiare a tuo piacimento\n\n".
				 "Qualora avessi ricevuto questa e-mail per errore, segnalalo rispondendo a questo messaggio";
			
			$msg = "L'iscrizione è stata registrata con successo ma non è stato possibile inviarti la password tramite e-mail\n".
				 "Le informazioni per permetterti l'accesso ai servizi offerti da UniversiBO sono:\n".
				 "Username: ".$new_user->getUsername()."\n".
				 "Password: ".$randomPassword."\n\n";
			
			if(!$mail->Send()) Error::throwError(_ERROR_DEFAULT,array('id_utente' => $session_user->getIdUser(), 'msg'=>$msg, 'file'=>__FILE__, 'line'=>__LINE__));
			
			$template->assign('regStudente_thanks',"Benvenuto \"".$new_user->getUsername()."\"!!\n \nL'iscrizione è stata registrata con successo.\nLe informazioni per permetterti l'accesso ai servizi offerti dal portale sono state inviate al tuo indirizzo e-mail di ateneo\n".
									'Per qualsiasi problema o spiegazioni contatta lo staff all\'indirizzo [email]'.$fc->getAppSetting('infoEmail').'[/email].');
			
			//elimino la password
			$randomPassword = '';
			$mail->Body = '';
			$msg = '';
			
			return 'success';
			
		}
		
		$array_livelli = array();
		$array_nomi = User::groupsNames();
		foreach($array_nomi as $key => $value)
		{
			if ($key != User::OSPITE && $key != User::DOCENTE)
				$array_livelli[$key] = $value; 
		}
		// riassegna valori form
		$template->assign('f34_username',	$f34_username);
		$template->assign('f34_livelli',	$array_livelli);
		$template->assign('f34_livello',	$f34_livello);
		$template->assign('f34_email',		$f34_email);
		$template->assign('f34_submit',		'Registra');
		
		return 'default';
		
	}
}
