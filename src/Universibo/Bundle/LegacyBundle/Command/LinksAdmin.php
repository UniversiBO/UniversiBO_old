<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Error;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;

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
        $template->assign('common_canaleURI', $router->generate('universibo_legacy_myuniversibo'));
        $template->assign('common_langCanaleNome', 'indietro');

        $idCanale = $this->getRequest()->attributes->get('id_canale');

        $referente = false;

        $user_ruoli = $user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();
        $ruoli = array();
        $arrayPublicUsers = array();

        $canale = Canale::retrieveCanale($idCanale);
        $id_canale = $canale->getIdCanale();

        $channelRouter = $this->get('universibo_legacy.routing.channel');
        $template->assign('common_canaleURI', $channelRouter->generate($canale));
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

        $this->executePlugin('ShowLinksExtended', array('id_canale' => $idCanale));
        $template->assign('add_link_uri', $router->generate('universibo_legacy_link_add', array('id_canale' => $idCanale)));
        //		$this->executePlugin('ShowTopic', array('reference' => 'ruoliadmin'));
        return 'default';
    }
}
