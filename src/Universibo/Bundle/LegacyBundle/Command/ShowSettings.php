<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\Framework\Error;
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
        $router = $this->get('router');

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => 0,
                            'msg' => "Non hai i diritti per accedere alla pagina\n la sessione potrebbe essere terminata",
                            'file' => __FILE__, 'line' => __LINE__,
                            'log' => false, 'template_engine' => &$template));
        }

        if ($utente->hasRole('ROLE_ADMIN')) {
            $template->assign('showSettings_showAdminPanel', 'true');
        } else {
            $template->assign('showSettings_showAdminPanel', 'false');
        }

        $preferences = array (
                '[url='.$router->generate('universibo_legacy_personal_files').']I miei file[/url]',
                '[url='.$router->generate('universibo_website_profile_edit').']Profilo[/url]',
                '[url='.$router->generate('universibo_legacy_user', array('id_utente' => $utente->getId())).']Modifica MyUniversiBO[/url]',
                '[url=https://www.dsa.unibo.it/AccessoPostaStudenti type=extern]Mail di ateneo[/url]',
        );

        if ($utente->hasRole('ROLE_MODERATOR') || $utente->hasRole('ROLE_ADMIN')) {
            $preferences[] = '[url='.$router->generate('universibo_legacy_contact_professors').']Docenti da contattare[/url]';
        }

        $template->assign('showSettings_langPreferences', $preferences);

        $template->assign('showSettings_langTitleAlt', 'MyUniversiBO');
        $template
                ->assign('showSettings_langIntro',
                        'Ora ti trovi nella tua pagina personale.
Tramite questa pagina potrai modificare il tuo profilo, le tue impostazioni personali ed avere un accesso veloce e personalizzato alle informazioni scegliendo i contenuti e il loro formato tramite le tue [b]Preferenze[/b].');

        $template
                ->assign('showSettings_langAdmin',
                        array(
                                '[url=https://www.universibo.unibo.it/phppgadmin/]DB Postgresql locale[/url]'));

        return 'default';
    }
}
