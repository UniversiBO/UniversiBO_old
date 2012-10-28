<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Error;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\Ruolo;

/**
 * RuoliAdminEdit: modifica il ruolo di un utente in un canale
 *
 *
 * @package universibo
 * @subpackage commands
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class RuoliAdminEdit extends UniversiboCommand
{
    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $router = $this->get('router');
        $ruoloRepo = $this->get('universibo_legacy.repository.ruolo');

        $user = $this->get('security.context')->getToken()->getUser();

        $referente = false;

        $user_ruoli = $user instanceof User ? $ruoloRepo->findByIdUtente($user->getId()) : array();
        $ruoli = array();
        $arrayPublicUsers = array();

        $request = $this->getRequest();
        $channelId = $request->get('id_canale');

        $channelRepo = $this->get('universibo_legacy.repository.canale2');
        $channel = $channelRepo->find($channelId);

        if (!$channel instanceof Canale) {
            throw new NotFoundHttpException('Channel not foun');
        }

        $userRepo = $this->get('universibo_core.repository.user');

        $targetUserId = $request->get('id_utente');
        $target_user = $userRepo->find($targetUserId);
        $target_username = $target_user->getUsername();
        $target_userUri = $router->generate('universibo_legacy_user', array('id_utente' => $target_user->getId()));

        $channelRouter = $this->get('universibo_legacy.routing.channel');
        $template->assign('common_canaleURI', $channelRouter->generate($channel));
        $template->assign('common_langCanaleNome', 'a '.$channel->getTitolo());

        if (array_key_exists($channelId, $user_ruoli)) {
            $ruolo = $user_ruoli[$channelId];
            $referente = $ruolo->isReferente();
        }

        if (!$this->get('security.context')->isGranted('ROLE_ADMIN') && !$referente ) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Not allowed to manage roles');
        }

        if (!$this->get('security.context')->isGranted('ROLE_ADMIN') && $user->getId() == $target_user->getId() )
            Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getId(), 'msg' => 'non e` permesso modificare i propri diritti in una pagina', 'file' => __FILE__, 'line' => __LINE__));

        $target_ruoli = $ruoloRepo->findByIdUtente($target_user->getId());
        if (!array_key_exists($channelId, $target_ruoli))
            $target_ruolo = null;
        else
            $target_ruolo = $target_ruoli[$channelId];

        $success = false;
        $f17_accept = false;
        //postback
        if (array_key_exists('f17_submit', $_POST)  ) {

            if (!array_key_exists('f17_livello', $_POST) )
                Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getId(), 'msg' => 'Il form inviato non e` valido', 'file' => __FILE__, 'line' => __LINE__));

            if ($_POST['f17_livello'] != 'none' && $_POST['f17_livello'] != 'M' && $_POST['f17_livello'] != 'R' )
                Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getId(), 'msg' => 'Il form inviato non e` valido', 'file' => __FILE__, 'line' => __LINE__));

            if ($target_ruolo == null) {
                $nascosto = false;
                $ruolo = new Ruolo($target_user->getId(), $channelId, '' , time(), $_POST['f17_livello'] == 'M', $_POST['f17_livello'] == 'R', true, NOTIFICA_ALL, $nascosto);
                $ruolo->insertRuolo();

                $success = true;
            } else {
                $target_ruolo->updateSetModeratore($_POST['f17_livello'] == 'M');
                $target_ruolo->updateSetReferente($_POST['f17_livello'] == 'R');
                $target_ruolo->setMyUniversiBO(true);
                $target_ruolo->updateRuolo();

                $success = true;
            }

        }

        if ($target_ruolo == null)
            $tpl_livello = 'none';
        else
            $tpl_livello = ($target_ruolo->isReferente()) ? 'R' :  (($target_ruolo->isModeratore()) ? 'M' : 'none');

        $template->assign('ruoliAdminEdit_userLivello', $tpl_livello);

        $template->assign('ruoliAdminEdit_username', $target_username);
        $template->assign('ruoliAdminEdit_userUri', $target_userUri);

        $template->assign('ruoliAdminEdit_langAction', "Modifica i diritti nella pagina\n".$channel->getTitolo());
        $template->assign('ruoliAdminEdit_langSuccess', '');
        if ($success == true)
            $template->assign('ruoliAdminEdit_langSuccess', 'Modifica eseguita con successo');

        $this->executePlugin('ShowTopic', array('reference' => 'filescollabs'));

        return 'default';
    }

}
