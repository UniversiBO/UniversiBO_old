<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\User;

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
class ChangePassword extends UniversiboCommand
{
    public function execute()
    {
        $fc = $this->getFrontController();
        $template = $this->frontController->getTemplateEngine();
        $user = $this->getSessionUser();
        if ($user->isOspite()) {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => 'La modifica della password non puo` essere eseguita da utenti con livello ospite.'
                                    . "\n"
                                    . 'La sessione potrebbe essere scaduta, eseguire il login',
                            'file' => __FILE__, 'line' => __LINE__));
        }

        $template
                ->assign('changePassword_langChangePasswordAlt',
                        'Modifica Password');
        //		$template->assign('changePassword_langUsername','Username:');
        $template->assign('changePassword_langOldPassword', 'Vecchia password:');
        $template->assign('changePassword_langNewPassword', 'Nuova password:');
        $template
                ->assign('changePassword_langReNewPassword',
                        'Conferma nuova password:');
        $template
                ->assignUnicode('changePassword_langInfoChangePassword',
                        'Per modificare la propria password inserire i dati relativi al proprio username e alla vecchia password.'
                                . "\n"
                                . 'Nei campi successivi riscrivere due volte la nuova password che si è scelto per evitare errori di battitura.');
        $template
                ->assignUnicode('changePassword_langHelp',
                        'Per qualsiasi problema o spiegazioni contattate lo staff all\'indirizzo [email]'
                                . $fc->getAppSetting('infoEmail') . '[/email].'
                                . "\n"
                                . 'In ogni caso non comunicate mai le vostre password di ateneo, lo staff non è tenuto a conoscerle');

        // valori default form
        //		$f6_username =	'';
        $f6_old_password = '';
        $f6_new_password1 = '';
        $f6_new_password2 = '';

        $f6_accept = false;

        if (array_key_exists('f6_submit', $_POST)) {
            $f6_accept = true;

            //			//var_dump($_POST);
            //			if ( !array_key_exists('f6_username', $_POST) ||
            //				 !array_key_exists('f6_old_password', $_POST) ||
            //				 !array_key_exists('f6_new_password1', $_POST) ||
            //				 !array_key_exists('f6_new_password2', $_POST) )
            if (!array_key_exists('f6_old_password', $_POST)
                    || !array_key_exists('f6_new_password1', $_POST)
                    || !array_key_exists('f6_new_password2', $_POST)) {
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il form inviato non e` valido',
                                'file' => __FILE__, 'line' => __LINE__));
                $f6_accept = false;
            }

            //			//username
            //			if ($_POST['f6_username'] == '') {
            //				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Inserire il proprio username','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
            //				$f6_accept = false;
            //			}
            //			elseif ( !User::isUsernameValid( $_POST['f6_username'] ) ) {
            //				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Nello username sono permessi fino a 25 caratteri alfanumerici con lettere accentate, spazi, punti, underscore','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
            //				$f6_accept = false;
            //			}
            //			elseif ( $this->sessionUser->getUsername() != $_POST['f6_username'] ) { // && !$this->sessionUser->isAdmin()
            //				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Lo username inserito non puo` essere differente dal proprio username, non e` permesso cambiare la password di altri utenti','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
            //				$f6_accept = false;
            //			}
            //			elseif ( !User::usernameExists( $_POST['f6_username'] ) ) {
            //				Error::throwError(_ERROR_NOTICE,array('id_utente' => $user->getIdUser(), 'msg'=>'Lo username richiesto non e` registrato da nessun utente','file'=>__FILE__,'line'=>__LINE__,'log'=>false ,'template_engine'=>&$template ));
            //				$f6_accept = false;
            //			}
            //			else $q6_username = $f6_username = $_POST['f6_username'];

            //old password
            if ($_POST['f6_old_password'] == '') {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Inserire la vecchia password',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false, 'template_engine' => &$template));
                $f6_accept = false;
            } elseif (strlen($_POST['f6_old_password']) > 50) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'La lunghezza massima della password accettata dal sistema e` di massimo 50 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false, 'template_engine' => &$template));
                $f6_accept = false;
            } else
                $q6_old_password = $f6_old_password = $_POST['f6_old_password'];

            //new password 1
            if ($_POST['f6_new_password1'] == '') {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Inserire la nuova password',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false, 'template_engine' => &$template));
                $f6_accept = false;
            } elseif (strlen($_POST['f6_new_password1']) > 50) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'La lunghezza massima della password accettata dal sistema e` di massimo 50 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false, 'template_engine' => &$template));
                $f6_accept = false;
            } else
                $q6_new_password1 = $f6_new_password1 = $_POST['f6_new_password1'];

            //new password 2
            if ($_POST['f6_new_password2'] == '') {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Inserire la conferma della nuova password',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false, 'template_engine' => &$template));
                $f6_accept = false;
            } elseif (strlen($_POST['f6_new_password2']) > 50) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'La lunghezza massima della password accettata dal sistema e` di massimo 50 caratteri',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false, 'template_engine' => &$template));
                $f6_accept = false;
            } else
                $q6_new_password2 = $f6_new_password2 = $_POST['f6_new_password2'];

            //new password 1&2
            if ($_POST['f6_new_password1'] != $_POST['f6_new_password2']) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Le nuove password inserite non sono uguali, fare attenzione ad errori di battitura',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false, 'template_engine' => &$template));
                $q6_new_password2 = $f6_new_password2 = $q6_new_password1 = $f6_new_password1 = '';
                $f6_accept = false;
            }

        }

        if ($f6_accept == true) {
            $user = User::selectUserUsername($user->getUsername());

            if ($user->updatePassword($q6_new_password1, true) == false)
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Si e` verificato un errore durante l\'aggiornamento della password relativa allo username '
                                        . $q6_username, 'file' => __FILE__,
                                'line' => __LINE__));

            $forum = $this->getContainer()->get('universibo_legacy.forum.api');
            $forum->updatePassword($user, $q6_new_password1);
            //	Error::throwError(_ERROR_DEFAULT,array('msg'=>'Si e` verificato un errore durante la modifica della password sul forum relativa allo username '.$q6_username,'file'=>__FILE__,'line'=>__LINE__));

            $template
                    ->assignUnicode('changePassword_thanks',
                            "La password è stata cambiata con successo, si consiglia di testarne il corretto funzionamento.\n"
                                    . 'Per qualsiasi problema o spiegazioni contatta lo staff all\'indirizzo [email]'
                                    . $fc->getAppSetting('infoEmail')
                                    . '[/email].');

            //elimino la password
            $q6_new_password1 = '';
            $q6_new_password2 = '';
            $f6_new_password1 = '';
            $f6_new_password2 = '';

            return 'success';

        }

        // riassegna valori form
        //		$template->assign('f6_username',	$f6_username);
        $template->assign('f6_old_password', '');
        $template->assign('f6_new_password1', $f6_new_password1);
        $template->assign('f6_new_password2', $f6_new_password2);
        $template->assign('f6_submit', 'Invia');

        return 'default';
    }
}
