<?php
namespace UniversiBO\Bundle\LegacyBundle\Command;
use \Error;
use UniversiBO\Bundle\LegacyBundle\App\UniversiboCommand;
use UniversiBO\Bundle\LegacyBundle\Entity\Collaboratore;
/**
 * ShowContacts is an extension of UniversiboCommand class.
 *
 * It shows Contacts page
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Fabrizio Pinto
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Cristina Valent
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class ShowCollaboratore extends UniversiboCommand
{
    function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $user = $this->getSessionUser();
        if (!array_key_exists('id_coll', $_GET)
                && !ereg('^([0-9]{1,10})$', $_GET['id_coll']))
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => 'L\'utente cercato non e` valido',
                            'file' => __FILE__, 'line' => __LINE__));

        $contacts_path = $frontcontroller->getAppSetting('contactsPath');

        $collaboratore = Collaboratore::selectCollaboratore($_GET['id_coll']);

        if (!$collaboratore)
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $user->getIdUser(),
                            'msg' => 'Non ci sono informazioni sul collaboratore scelto',
                            'file' => __FILE__, 'line' => __LINE__,
                            'template_engine' => &$template));

        $curr_user = $collaboratore->getUser();
        if (($user->getIdUser()) == ($collaboratore->getIdUtente())) {
            $modifica_link = '';
            $modifica = "modifica";
        } else {
            $modifica_link = '';
            $modifica = "";
        }

        $arrayContatti = array('username' => $curr_user->getUsername(),
                'intro' => $collaboratore->getIntro(),
                'ruolo' => $collaboratore->getRuolo(),
                'email' => $curr_user->getEmail(),
                'recapito' => $collaboratore->getRecapito(),
                'obiettivi' => $collaboratore->getObiettivi(),
                'foto' => $collaboratore->getFotoFilename(),
                'id_utente' => $collaboratore->getIdUtente(),
                'modifica_link' => $modifica_link, 'modifica' => $modifica);

        $template->assign('collaboratore', $arrayContatti);

        $template
                ->assign('collaboratore_langAltTitle', 'Scheda Informativa di');
        //$template->assign('collaboratore_langIntro', 'Scheda informativa di');
        $template->assign('contacts_path', $contacts_path);

        return 'default';
    }
}
