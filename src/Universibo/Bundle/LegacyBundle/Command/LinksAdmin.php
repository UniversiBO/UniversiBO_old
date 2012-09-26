<?php
namespace Universibo\Bundle\LegacyBundle\Command;
use Universibo\Bundle\WebsiteBundle\Entity\User;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use \Error;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * LinksAdminSearch: permette la ricerca di links all'interno di un canale
 *
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class LinksAdmin extends UniversiboCommand
{

    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();

        $router = $this->get('router');

        $user = $this->get('security.context')->getToken()->getUser();
        $userId = $user instanceof User ? $user->getId() : 0;
        $template->assign('common_canaleURI', $router->generate('universibo_legacy_default', array('do' => 'ShowMyUniversiBO')));
        $template->assign('common_langCanaleNome', 'indietro');

        $referente = false;

        $user_ruoli = $user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();
        $ruoli = array();
        $arrayPublicUsers = array();

        if (!array_key_exists('id_canale', $_GET)|| !preg_match('/^([0-9]{1,9})$/', $_GET['id_canale'])) {
            throw new NotFoundHttpException('Invalid ID');
        }

        $canale = Canale::retrieveCanale($_GET['id_canale']);
        $id_canale = $canale->getIdCanale();

        $template->assign('common_canaleURI', $canale->showMe());
        $template->assign('common_langCanaleNome', 'a ' . $canale->getTitolo());

        if (array_key_exists($id_canale, $user_ruoli)) {
            $ruolo = $user_ruoli[$id_canale];
            $referente = $ruolo->isReferente();
        }

        if (!$this->get('security.context')->isGranted('ROLE_ADMIN') && !$referente)
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $userId,
                            'msg' => "Non hai i diritti per modificare i diritti degli utenti su questa pagina.\nLa sessione potrebbe essere scaduta.",
                            'file' => __FILE__, 'line' => __LINE__));

        $this->executePlugin('ShowLinksExtended', array('id_canale' => $_GET['id_canale']));
        $template->assign('add_link_uri', $router->generate('universibo_legacy_default', array('do' => 'LinkAdd', 'id_canale' => $_GET['id_canale'])));
        //		$this->executePlugin('ShowTopic', array('reference' => 'ruoliadmin'));
        return 'default';
    }
}
