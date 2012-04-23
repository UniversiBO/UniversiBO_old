<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;

use \DB;
use \Error;

use UniversiBO\Bundle\LegacyBundle\Entity\User;
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;
use UniversiBO\Bundle\LegacyBundle\App\ForumApi;

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
class NewPasswordDocente extends UniversiboCommand 
{
	function execute()
	{
		$fc = $this->getFrontController();
		$template = $this->frontController->getTemplateEngine();
		
		$session_user = $this->getSessionUser();
		
		if (!$session_user->isAdmin())
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $session_user->getIdUser(), 'msg'=>'La generazione di una nuova password dei docenti e` possibile solo ad utenti amministratori.'."\n".'La sessione potrebbe essere scaduta, eseguire il login','file'=>__FILE__,'line'=>__LINE__));
		
		
		$username_allowed = explode(';',$fc->getAppSetting('passwordDocentiAdmin'));
		if (!in_array($session_user->getUsername(), $username_allowed ))
			Error::throwError(_ERROR_DEFAULT,array('id_utente' => $session_user->getIdUser(), 'msg'=>'La generazione di una nuova password dei docenti e` possibile solo a '.implode(', ',$username_allowed)."\n".'La sessione potrebbe essere scaduta, eseguire il login','file'=>__FILE__,'line'=>__LINE__));
		
		if ( !array_key_exists('id_utente', $_GET)  || !preg_match('/^([0-9]{1,9})$/', $_GET['id_utente']))
			Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $session_user->getIdUser(), 'msg' => 'L\'id dell\'utente richiesto non e` valido', 'file' => __FILE__, 'line' => __LINE__));

		$docente = User::selectUser($_GET['id_utente']);
		
		if ($docente === false || ($docente->isDocente() == false &&  $docente->isTutor() == false))
			Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $session_user->getIdUser(), 'msg' => 'L\'id dell\'utente richiesto non appartiene ad un docente o ad un tutor', 'file' => __FILE__, 'line' => __LINE__));
		
		$livelli = implode(', ',$docente->getUserGroupsNames());
		$username = $docente->getUsername();
		$email =  $docente->getEmail();

		$template->assign('newPasswordDocente_livelli',$livelli);
		$template->assign('newPasswordDocente_username',$username);
		$template->assign('newPasswordDocente_email',$email);

//		$template->assign('newPasswordDocente_langNewPassword','Nuova password:');
//		$template->assign('newPasswordDocente_langReNewPassword','Conferma nuova password:');
//		$template->assign('newPasswordDocente_langInfoChangePassword','Per modificare la propria password inserire i dati relativi al proprio username e alla vecchia password.'."\n".'Nei campi successivi riscrivere due volte la nuova password che si ? scelto per evitare errori di battitura.');
//		$template->assign('newPasswordDocente_langHelp','Per qualsiasi problema o spiegazioni contattate lo staff all\'indirizzo [email]'.$fc->getAppSetting('infoEmail').'[/email].'."\n".
//							'In ogni caso non comunicate mai le vostre password di ateneo, lo staff non ? tenuto a conoscerle');
		// valori default form
		
		$f21_accept = false;
		
		if ( array_key_exists('f21_submit', $_POST)  )
			$f21_accept = true;
		
		
		if ( $f21_accept == true )
		{
			
			$randomPassword = User::generateRandomPassword();
			
			if ($docente->updatePassword($randomPassword,true) == false)
				Error::throwError(_ERROR_DEFAULT,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Si e` verificato un errore durante l\'aggiornamento della password','file'=>__FILE__,'line'=>__LINE__));
			
			$forum = new ForumApi();
			$forum->updatePassword($docente, $randomPassword);
			//	Error::throwError(_ERROR_DEFAULT,array('msg'=>'Si ? verificato un errore durante la modifica della password sul forum relativa allo username '.$q6_username,'file'=>__FILE__,'line'=>__LINE__));
			
			$template->assignUnicode('newPasswordDocente_thanks',"La password è stata cambiata con successo.\n");
			
			
			$mail = $fc->getMail();
			$mail->AddAddress($email);
			
			
            $mail->Subject = "Registrazione UniversiBO";

			$mail->Body = ($docente->isDocente())
				?
					"Gentile docente,\n".
					"E' stata inoltrata una richiesta di aggiornamento della sua password per l'accesso ad UniversiBO.\n\n".
					"Le nuove informazioni per permetterle l'accesso ai servizi offerti sono:\n".
					"Username: ".$username."\n".
					"Password: ".$randomPassword."\n".
					"Questa password e' stata generata in modo casuale, sul sito e' disponibile la funzionalita' per poterla cambiare\n\n".
					"Se vuole iscrivere dei suoi \"assistenti\" risponda a questa mail con i loro nomi e indirizzi email ed uno username di loro gradimento.\n".
					"Provvederemo ad iscriverli al piu' presto e a dar loro i diritti opportuni sulle sue pagine.\n\n".
					"Per qualsiasi problema non esiti a contattarci\n".
					"Grazie per la disponibilita'\n\n".
					"Qualora avesse ricevuto questa e-mail per errore lo segnali rispondendo immediatamente a questo messaggio\n\n"
				:
					"Gentile tutor,\n".
					"E' stata inoltrata una richiesta di aggiornamento della sua password per l'accesso ad UniversiBO.\n\n".
					"Le nuove informazioni per permetterle l'accesso ai servizi offerti sono:\n".
					"Username: ".$username."\n".
					"Password: ".$randomPassword."\n".
					"Questa password e' stata generata in modo casuale, sul sito e' disponibile la funzionalita' per poterla cambiare\n\n".
					"Qualora avesse ricevuto questa e-mail per errore lo segnali rispondendo immediatamente a questo messaggio\n\n"
				;

			if(!$mail->Send()) 
				Error::throwError(_ERROR_DEFAULT,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Attenzione!!! La password e` stata aggiornata su database ma potrebbe essersi verificato un errore durante la spedizione dell\'email!!'."\nPassword: $randomPassword",'file'=>__FILE__,'line'=>__LINE__));
 			
			return 'success';
			
		}
		
		// riassegna valori form

		return 'default';
		
	}
}
