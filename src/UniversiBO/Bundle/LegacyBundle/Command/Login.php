<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;
use UniversiBO\Bundle\LegacyBundle\App\ForumApi;
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;
use UniversiBO\Bundle\LegacyBundle\Entity\User;
/**
 * Login is an extension of UniversiboCommand class.
 *
 * Manages Users Login/Logout actions
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class Login extends UniversiboCommand
{
    function execute()
    {
        $fc = $this->getFrontController();
        $template = $this->frontController->getTemplateEngine();
        $user = $this->getSessionUser();

        if (array_key_exists('referer', $_GET)) {
            $referer = $_GET['referer'];
        } else {
            $referer = (array_key_exists('f1_referer', $_POST)) ? $_POST['f1_referer']
                    : (array_key_exists('HTTP_REFERER', $_SERVER)) ? $_SERVER['HTTP_REFERER']
                            : '';
        }
        if (array_key_exists('f1_username', $_POST))
            $_POST['f1_username'] = trim($_POST['f1_username']);
        else
            Error::throwError(_ERROR_NOTICE,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => 'Username non inserito', 'file' => __FILE__,
                            'line' => __LINE__, 'log' => false,
                            'template_engine' => &$template));

        if (array_key_exists('f1_submit', $_POST)) {
            if (!$user->isOspite()) {
                Error::throwError(_ERROR_DEFAULT,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Il login pu� essere eseguito solo da utenti che non hanno ancora eseguito l\'accesso',
                                'file' => __FILE__, 'line' => __LINE__));
            }

            if (!User::isUsernameValid($_POST['f1_username']))
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $user->getIdUser(),
                                'msg' => 'Username non valido',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => false, 'template_engine' => &$template));

            $userLogin = User::selectUserUsername($_POST['f1_username']);

            if ($userLogin === false || $userLogin->isEliminato()) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => '0',
                                'msg' => 'Non esistono utenti con lo username inserito',
                                'file' => __FILE__, 'line' => __LINE__,
                                'log' => true, 'template_engine' => &$template));
            } elseif (!$userLogin->matchesPassword($_POST['f1_password'], true)) {
                Error::throwError(_ERROR_NOTICE,
                        array('id_utente' => $userLogin->getIdUser(),
                                'msg' => 'Password errata', 'file' => __FILE__,
                                'line' => __LINE__, 'log' => true,
                                'template_engine' => &$template));
            } else {
                session_destroy();
                session_start();
                $_POST['f1_password'] = ''; //resettata per sicurezza
                $_SESSION['user'] = array();
                $_SESSION['user'] = serialize($userLogin);
                $_SESSION['referer'] = $referer;
                FrontController::redirectCommand('InteractiveCommandHandler');

                //				// questa parte � in InteractiveCommandHandler
                //				$userLogin->updateUltimoLogin(time());
                //				$this->setSessionIdUtente($userLogin->getIdUser());
                //				$fc->setStyle($userLogin->getDefaultStyle());
                //
                //				$forum = new ForumApi();
                //				$forum->login($userLogin);
                //
                //var_dump($referer);

                //				if ( !strstr($referer, 'forum') && ( !strstr($referer, 'do') || strstr($referer, 'do=ShowHome')  || strstr($referer, 'do=ShowError') || strstr($referer, 'do=Login') || strstr($referer, 'do=RegStudente')))
                //					FrontController::redirectCommand('ShowMyUniversiBO');
                //				else if (strstr($referer, 'forum'))
                //					FrontController::redirectUri($forum->getMainUri());
                //				else
                //					FrontController::redirectUri($referer);

            }
            $_POST['f1_password'] = ''; //resettata per sicurezza

        }

        $f1_username = (array_key_exists('f1_username', $_POST)) ? ''
                : $_POST['f1_username'] = '';
        $f1_password = '';

        $template->assign('login_langLogin', 'Login');
        $template->assign('f1_referer_value', $referer);
        $template->assign('f1_username_value', $_POST['f1_username']);
        $template->assign('f1_password_value', '');

        return;

    }

    private function isSymfony()
    {
        return array_key_exists('symfony', $_GET) && $_GET['symfony'];
    }
}
