<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
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
    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $user = $this->get('security.context')->getToken()->getUser();

        $username = $this->getRequest()->attributes->get('username');
        $userRepo = $this->get('universibo_website.repository.user');

        $collabUser = $userRepo->findOneByUsername($username);

        if (!$collabUser instanceof User) {
            throw new NotFoundHttpException('Invalid Username');
        }

        $contacts_path = $frontcontroller->getAppSetting('contactsPath');

        $collaboratore = $this->get('universibo_legacy.repository.collaboratore')->findOneByUser($collabUser);

        if (!$collaboratore) {
            throw new NotFoundHttpException('Collaborator not found');
        }

        $curr_user = $collabUser;
        if ($user instanceof User && $user->getId() == $collaboratore->getIdUtente()) {
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

        $template->assign('collaboratore_langAltTitle', 'Scheda Informativa di');
        //$template->assign('collaboratore_langIntro', 'Scheda informativa di');
        $template->assign('contacts_path', $contacts_path);

        return 'default';
    }
}
