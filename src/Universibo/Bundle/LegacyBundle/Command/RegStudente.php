<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\Entity\User;

use \Error;
use Universibo\Bundle\LegacyBundle\App\Constants;
use Universibo\Bundle\LegacyBundle\App\ForumApi;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Command\InteractiveCommand\InformativaPrivacyInteractiveCommand;
/**
 * RegStudente is an extension of UniversiboCommand class.
 *
 * Si occupa della registrazione degli studenti
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class RegStudente extends UniversiboCommand
{
    public function execute()
    {
        $fc = $this->getFrontController();
        $template = $this->frontController->getTemplateEngine();

        $session_user = $this->getSessionUser();
        if (!$session_user->isOspite()) {
            Error::throwError(_ERROR_DEFAULT,array('id_utente' => $session_user->getIdUser(), 'msg'=>'L\'iscrizione puo` essere richiesta solo da utenti che non hanno ancora eseguito l\'accesso','file'=>__FILE__,'line'=>__LINE__));
        }

        $template->assign('f4_submit',		'Registra');
        $template->assign('regStudente_langRegAlt','Registrazione');
        $template->assign('regStudente_langMail','e-mail di ateneo:');
        $template->assign('regStudente_langPassword','Password dell\' email d\' ateneo:');
        $template->assign('regStudente_langUsername','Username scelto per UniversiBO:');
        $template->assign('regStudente_domain','@studio.unibo.it');
        $template->assignUnicode('regStudente_langInfoUsername','E\' necessario scegliere uno Username che sarà utilizzato per i futuri accessi e che sarà anche il vostro nome identificativo all\'interno di UniversiBO.[b]Non sarà possibile cambiare username in seguito[/b].
Il sistema genererà una password casuale che sarà inviata alla vostra casella e-mail d\'ateneo.');
        $template->assignUnicode('regStudente_langInfoReg','Per garantire la massima sicurezza, l\'identificazione degli studenti al loro primo accesso avviene tramite la casella e-mail d\'ateneo e la relativa password.
Se non possedete ancora la e-mail di ateneo andate sul sito [url]http://www.unibo.it[/url] cliccate sul "Login" in alto a destra e seguite le istruzioni.
Per problemi indipendenti da noi [b]la casella e-mail verrà creata nelle 24 ore successive[/b] e potete accedervi tramite il sito [url]https://posta.studio.unibo.it[/url], vi preghiamo di apettare che la mail di ateneo sia attiva prima di iscrivervi.');
        $template->assign('regStudente_langReg','Regolamento per l\'utilizzo dei servizi:');
        $template->assign('regStudente_langPrivacy','Informativa sulla privacy:');
        $template->assign('regStudente_langConfirm','Confermo di aver letto il regolamento');
        $template->assignUnicode('regStudente_langHelp','Per qualsiasi problema o spiegazioni contattate lo staff all\'indirizzo [email]'.$fc->getAppSetting('infoEmail').'[/email].'."\n".
                            'In ogni caso non comunicate mai le vostre password di ateneo, lo staff non è tenuto a conoscerle');

        // valori default form
        $f4_username =	'';
        $f4_password =	'';
        $f4_ad_user =	'';

        $f4_accept = false;

        if ( array_key_exists('f4_submit', $_POST)  ) {
            $f4_accept = true;
            //var_dump($_POST);
            if ( !array_key_exists('f4_privacy', $_POST) ||
                 !array_key_exists('f4_regolamento', $_POST) ||
                 !array_key_exists('f4_username', $_POST) ||
                 !array_key_exists('f4_password', $_POST) ||
                 !array_key_exists('f4_ad_user', $_POST) )
            {
                Error::throwError(_ERROR_DEFAULT,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Il form inviato non � valido','file'=>__FILE__,'line'=>__LINE__ ));
                $f4_accept = false;
            }

            //ad_user
            if ( $_POST['f4_ad_user'] == '' ) {
                Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Inserire la e-mail di ateneo','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
                $f4_accept = false;
            } elseif ( strlen($_POST['f4_ad_user']) > 30 ) {
                Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Lo username di ateneo indicato pu� essere massimo 30 caratteri','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
                $f4_accept = false;
            } elseif (preg_match('/@studio\.unibo\.it$/',$_POST['f4_ad_user'])) {
                Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Non inserire il suffisso "@studio.unibo.it" nella email di ateneo','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
                $f4_accept = false;
            } elseif (!eregi('^([[:alnum:]])+\.[[[:alnum:]]+$',$_POST['f4_ad_user'])) {
                Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>"La mail di ateneo ".$_POST['f4_ad_user']."@studio.unibo.it appartiene ad un utente gia` registrato.\nProbabilmente sei gia` registrato, utilizza la pagina Password Smarrita per recuperare la password",'file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
                $f4_accept = false;
            } elseif (User::activeDirectoryUsernameExists($_POST['f4_ad_user'].'@studio.unibo.it')) {
                Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'La mail di ateneo '.$_POST['f4_ad_user'].'@studio.unibo.it'.' appartiene ad un utente gia` registrato o non e` piu` autorizzata','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
                $f4_accept = false;
            } else {
                $f4_ad_user = strtolower($_POST['f4_ad_user']);
                $q4_ad_user = strtolower($f4_ad_user.'@studio.unibo.it');
            }

            //password
            if ( $_POST['f4_password'] == '' ) {
                Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Inserire la password della e-mail di ateneo','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
                $f4_accept = false;
            } elseif ( strlen($_POST['f4_password']) > 50 ) {
                Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'La lunghezza massima della password accettata dal sistema � di massimo 50 caratteri','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
                $f4_accept = false;
            } else $q4_password = $f4_password = $_POST['f4_password'];

            //username
            if ( $_POST['f4_username'] == '' ) {
                Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Scegliere uno username','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
                $f4_accept = false;
            } elseif ($_POST['f4_username']{0}==' ' || $_POST['f4_username']{strlen($_POST['f4_username']) - 1}==' ') {
                Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Non sono accettati spazi all\' inizio o alla fine dello username','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
                $f4_accept = false;
            } elseif ( !User::isUsernameValid( $_POST['f4_username'] ) ) {
                Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Nello username sono permessi fino a 25 caratteri alfanumerici con lettere accentate, spazi, punti, underscore','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
                $f4_accept = false;
            } elseif ( User::usernameExists( $_POST['f4_username'] ) ) {
                Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Lo username richiesto � gi� stato registrato da un altro utente','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
                $f4_accept = false;
            } else $q4_username = $f4_username = $_POST['f4_username'];

            //confirm
            if ( !array_key_exists('f4_confirm', $_POST)) {
                Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'E\' neccessario confermare il regolamento per potersi iscrivere','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
                $f4_accept = false;
            }

        }

        if ( $f4_accept == true ) {

            //controllo active directory
            $adl_host = $fc->getAppSetting('adLoginHost');
            $adl_port = $fc->getAppSetting('adLoginPort');
            if (! User::activeDirectoryLogin($f4_ad_user, 'studio.unibo.it', $q4_password, $adl_host, $adl_port ) ) {
                Error::throwError(_ERROR_NOTICE,array('id_utente' => $session_user->getIdUser(), 'msg'=>'L\'autenticazione tramite e-mail di ateneo ha fornito risultato negativo','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));

                return 'default';
            }

            $randomPassword = User::generateRandomPassword();
            $notifica = Constants::NOTIFICA_ALL;

            $new_user = new User(-1, User::STUDENTE, $q4_username ,$randomPassword, $q4_ad_user, $notifica, 0, $q4_ad_user, '', $fc->getAppSetting('defaultStyle') );

            if ($new_user->insertUser() == false)
                Error::throwError(_ERROR_DEFAULT,array('id_utente' => $session_user->getIdUser(), 'msg'=>'Si e` verificato un errore durente la registrazione dell\'account username '.$q4_username.' mail '.$q4_ad_user,'file'=>__FILE__,'line'=>__LINE__));

            $forum = new ForumApi();
            $forum->insertUser($new_user, $randomPassword);
            //	Error::throwError(_ERROR_DEFAULT,'msg'=>'Si ? verificato un errore durente la registrazione dell\'account username '.$q4_username.' mail '.$q4_ad_user,'file'=>__FILE__,'line'=>__LINE__));

            $mail = $fc->getMail();

            $mail->AddAddress($new_user->getEmail());

            $mail->Subject = "Registrazione UniversiBO";
            $mail->Body = "Benvenuto \"".$new_user->getUsername()."\"!!\nFai ora parte di UniversiBO, la community degli studenti dell'universita' di Bologna!\n\n".
                 "Per accedere al sito utilizza l'indirizzo ".$fc->getAppSetting('rootUrl')."\n\n".
                 "Le informazioni per permetterti l'accesso ai servizi offerti dal portale sono:\n".
                 "Username: ".$new_user->getUsername()."\n".
                 "Password: ".$randomPassword."\n\n".
                 "Questa password e' stata generata in modo casuale: sul sito  e' disponibile nella pagina delle tue impostazioni personali la funzionalita' per poterla cambiare a tuo piacimento\n\n".
                  "Dopo aver fatto il login puoi, modificare il tuo profilo personale per l'inoltro delle News dei tuoi esami preferiti in e-mail\n".
                  "Se desideri collaborare al progetto UniversiBO compila il questionario all'indirizzo ".$fc->getAppSetting('rootUrl')."/v2.php?do=ShowContribute \n\n".
                 "Qualora avessi ricevuto questa e-mail per errore, segnalalo rispondendo a questo messaggio";

            $msg = "L'iscrizione e` stata registrata con successo ma non e` stato possibile inviarti la password tramite e-mail\n".
                 "Le informazioni per permetterti l'accesso ai servizi offerti da UniversiBO sono:\n".
                 "Username: ".$new_user->getUsername()."\n".
                 "Password: ".$randomPassword."\n\n";

            if(!$mail->Send()) Error::throwError(_ERROR_DEFAULT,array('msg'=>$msg, 'file'=>__FILE__, 'line'=>__LINE__));

            $template->assignUnicode('regStudente_thanks',"Benvenuto \"".$new_user->getUsername()."\"!!\n \nL'iscrizione è stata registrata con successo.\n\nLe informazioni per permetterti l'accesso ai servizi offerti da UniversiBO sono state inviate al tuo indirizzo e-mail di ateneo\nPer controllare la tua posta d'ateneo vai a [url=https://outlook.com/ type=extern]Posta di ateneo[/url]\n\n".
            'Per qualsiasi problema o spiegazioni contatta lo staff all\'indirizzo [email]'.$fc->getAppSetting('infoEmail').'[/email].');

            //elimino la password
            $randomPassword = '';
            $mail->Body = '';
            $msg = '';

            return 'success';

        }
        $testoInformativa = InformativaPrivacyInteractiveCommand::getAttualeInformativaPrivacy ();
        // riassegna valori form
        $template->assign('f4_regolamento',	file_get_contents($fc->getAppSetting('regolamento')));
        $template->assign('f4_privacy',		$testoInformativa['testo']);
        $template->assign('f4_username',	$f4_username);
        $template->assign('f4_password',	'');
        $template->assign('f4_ad_user',		$f4_ad_user);
        $template->assign('f4_submit',		'Registra');

        $this->executePlugin('ShowTopic', array('reference' => 'iscrizione'));

        return 'default';

    }
}
