<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use \Error;
use Symfony\Component\HttpFoundation\Response;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;

/**
 * ShowMyUniversiBO is an extension of UniversiboCommand class.
 *
 * Mostra la MyUniversiBO dell'utente loggato, con le ultime 5 notizie e
 * gli ultimi 5 files presenti nei canali da lui aggiunti...
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @author Daniele Tiles
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */

class MyUniversiBORemove extends UniversiboCommand
{
    public function execute()
    {

        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $router = $this->get('router');
        $utente = $this->get('security.context')->getToken()->getUser();
        $router = $this->get('router');

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new Response('', 403);
        }

        $id_canale = $this->getRequest()->attributes->get('id_canale');
        $canale = Canale::retrieveCanale($id_canale);
        $template->assign('common_canaleURI', $canale->showMe($router));
        $template->assign('common_langCanaleNome', $canale->getNome());
        $template->assign('showUser', $router->generate('universibo_legacy_user', array('id_utente' => $utente->getId())));

        $ruoli = $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($utente->getId());
        $this->executePlugin('ShowTopic', array('reference' => 'myuniversibo'));

        if (array_key_exists($id_canale, $ruoli)) {
            $ruolo = $ruoli[$id_canale];
            $ruolo->setMyUniversiBO(false, true);

            $forum = $this->getContainer()->get('universibo_forum.dao.group');
            $forum->removeUserFromGroup($utente, $canale->getForumGroupId());

            return 'success';
        } else {
            Error::throwError(_ERROR_DEFAULT,
                    array('id_utente' => $utente->getId(),
                            'msg' => 'E\' impossibile trovare la pagina nel tuo elenco di MyUniversiBO',
                            'file' => __FILE__, 'line' => __LINE__));
        }
    }
}
