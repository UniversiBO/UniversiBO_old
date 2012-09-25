<?php
namespace Universibo\Bundle\LegacyBundle\Command;
use \Error;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * ShowSettings is an extension of UniversiboCommand class.
 *
 * Mostra i link agli strumenti per la modifica delle impostazioni personali
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class ShowSettings extends UniversiboCommand
{
    public function execute()
    {

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $utente = $this->get('security.context')->getToken()->getUser();

        if ($utente->isOspite()) {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $utente->getIdUser(),
                            'msg' => "Non hai i diritti per accedere alla pagina\n la sessione potrebbe essere terminata",
                            'file' => __FILE__, 'line' => __LINE__,
                            'log' => false, 'template_engine' => &$template));
        }

        if ($utente->hasRole('ROLE_ADMIN')) {
            $template->assign('showSettings_showAdminPanel', 'true');
        } else {
            $template->assign('showSettings_showAdminPanel', 'false');
        }

        if ($utente->hasRole('ROLE_COLLABORATOR') || $utente->hasRole('ROLE_ADMIN')) {
            $template
                    ->assign('showSettings_langPreferences',
                            array(
                                    '[url=/?do=ChangePassword]Modifica password[/url]',
                                    '[url=]Informazioni forum[/url]',
                                    '[url=/?do=ShowPersonalSettings]Profilo[/url]',
                                    '[url=/?do=ShowUser&id_utente='
                                            . $utente->getIdUser()
                                            . ']Modifica MyUniversiBO[/url]',
                                    '[url=https://outlook.com/ type=extern]Posta di ateneo[/url]',
                                    '[url=/?do=ShowContattiDocenti]Docenti da contattare[/url]'));
        } else {
            $template
                    ->assign('showSettings_langPreferences',
                            array(
                                    '[url=/?do=ChangePassword]Modifica password[/url]',
                                    '[url=]Informazioni forum[/url]',
                                    '[url=/?do=ShowPersonalSettings]Profilo[/url]',
                                    '[url=/?do=ShowUser&id_utente='
                                            . $utente->getIdUser()
                                            . ']Modifica MyUniversiBO[/url]',
                                    '[url=https://outlook.com/ type=extern]Posta di ateneo[/url]'));
        }

        $template->assign('showSettings_langTitleAlt', 'MyUniversiBO');
        $template
                ->assign('showSettings_langIntro',
                        'Ora ti trovi nella tua pagina personale.
Tramite questa pagina potrai modificare il tuo profilo, le tue impostazioni personali ed avere un accesso veloce e personalizzato alle informazioni scegliendo i contenuti e il loro formato tramite le tue [b]Preferenze[/b].');

        $template
                ->assign('showSettings_langAdmin',
                        array(
                                '[url=https://www.universibo.unibo.it/phpPgAdmin/]DB Postgresql locale[/url]',
                                '[url=/?do=RegUser]Iscrivi nuovo utente[/url]'));

        return 'default';
    }
}
