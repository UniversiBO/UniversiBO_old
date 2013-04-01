<?php
namespace Universibo\Bundle\LegacyBundle\Command;

use Universibo\Bundle\LegacyBundle\Framework\Error;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\MainBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\App\UniversiboCommand;
use Universibo\Bundle\LegacyBundle\Entity\Canale;

/**
 * RuoliAdminSearch: permette la ricerca di ruoli all'interno di un canale
 *
 *
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, {@link http://www.opensource.org/licenses/gpl-license.php}
 */
class RuoliAdminSearch extends UniversiboCommand
{
    public function execute()
    {
        $frontcontroller = $this->getFrontController();
        $template = $frontcontroller->getTemplateEngine();
        $router = $this->get('router');
        $user = $this->get('security.context')->getToken()->getUser();

        $referente = false;

        $user_ruoli = $user instanceof User ? $this->get('universibo_legacy.repository.ruolo')->findByIdUtente($user->getId()) : array();
        $userId = $user instanceof User ? $user->getId() : 0;
        $ruoli = array();
        $arrayPublicUsers = array();


        $id_canale = $this->getRequest()->attributes->get('id_canale');
        $canale = Canale::retrieveCanale($id_canale);

        if (!$canale instanceof Canale) {
            throw new NotFoundHttpException('Canale not found');
        }

        $channelRouter = $this->get('universibo_legacy.routing.channel');
        $template->assign('common_canaleURI', $channelRouter->generate($canale));
        $template->assign('common_langCanaleNome', 'a '.$canale->getTitolo());

        if (array_key_exists($id_canale, $user_ruoli)) {
            $ruolo = $user_ruoli[$id_canale];
            $referente = $ruolo->isReferente();
        }

        if (!$this->get('security.context')->isGranted('ROLE_ADMIN') && !$referente ) {
            throw new AccessDeniedHttpException('Not allowed to manage roles');
        }

        $userRepo = $this->get('universibo_main.repository.user');

        $translator = $this->get('universibo_legacy.translator.role_name');

        $f16_accept = false;
        //postback
        if (array_key_exists('f16_submit', $_POST)  ) {

            if (!array_key_exists('f16_username', $_POST) || !array_key_exists('f16_email', $_POST) )
                Error :: throwError(_ERROR_DEFAULT, array ('id_utente' => $user->getId(), 'msg' => 'Il form inviato non e` valido', 'file' => __FILE__, 'line' => __LINE__));

            $f16_accept = true;

            if ($_POST['f16_username'] == '' && $_POST['f16_email'] == '') {
                Error :: throwError(_ERROR_NOTICE, array ('id_utente' => $user->getId(), 'msg' => 'Specificare almeno uno dei due criteri di ricerca', 'file' => __FILE__, 'line' => __LINE__, 'log' => false, 'template_engine' => & $template));
                $f16_accept = false;
            }

            if ($_POST['f16_username'] == '')
                $f16_username = '%';
            else
                $f16_username = $_POST['f16_username'];

            if ($_POST['f16_email'] == '')
                $f16_email = '%';
            else
                $f16_email = $_POST['f16_email'];

            $roleRepo = $this->get('universibo_legacy.repository.ruolo');

            if ($f16_accept) {
                $users_search = $userRepo->search($f16_username, $f16_email);

                $users_search_keys = array_keys($users_search);
                foreach ($users_search_keys as $key) {
                    $ruoli_search  = $roleRepo->findByIdUtente($users_search[$key]->getId());

                    $contactUser = array();
                    $contactUser['utente_link']  = $router->generate('universibo_legacy_user', array('id_utente' => $users_search[$key]->getId()));
                    $contactUser['edit_link']  = $router->generate('universibo_legacy_role_admin_edit', array('id_canale' => $id_canale, 'id_utente' => $users_search[$key]->getId()));
                    $contactUser['nome']  =  $translator->getUserPublicGroupName($users_search[$key]);
                    $contactUser['label'] = $users_search[$key]->getUsername();

                    if (array_key_exists($id_canale, $ruoli_search)) {
                        $ruolo_search  = $ruoli_search[$id_canale];
                        $contactUser['ruolo'] = ($ruolo_search->isReferente()) ? 'R' :  (($ruolo_search->isModeratore()) ? 'M' : 'none');
                    } else {

                        $contactUser['ruolo'] = 'none';
                    }

                    $arrayPublicUsers[$translator->getUserPublicGroupName($users_search[$key],false)][] = $contactUser;

                }

            }

        }

        if (!$f16_accept) {
            $canale_ruoli = $canale->getRuoli();
            $ruoli_keys = array_keys($canale_ruoli);
            foreach ($ruoli_keys as $key) {
                if ($canale_ruoli[$key]->isReferente() || $canale_ruoli[$key]->isModeratore() ) {
                    $ruoli[] = $canale_ruoli[$key];

                    $user = $this->get('universibo_main.repository.user')->find($canale_ruoli[$key]->getId());
                    //var_dump($user);
                    $contactUser = array();
                    $contactUser['utente_link']  = $router->generate('universibo_legacy_user', array('id_utente' => $user->getId()));
                    $contactUser['edit_link']  = $router->generate('universibo_legacy_role_admin_edit', array('id_canale' => $id_canale, 'id_utente' => $user->getId()));
                    $contactUser['nome']  = $translator->getUserPublicGroupName($user);
                    $contactUser['label'] = $user->getUsername();
                    $contactUser['ruolo'] = ($canale_ruoli[$key]->isReferente()) ? 'R' :  (($canale_ruoli[$key]->isModeratore()) ? 'M' : 'none');
                    //var_dump($ruolo);
                    //$arrayUsers[] = $contactUser;
                    $arrayPublicUsers[$translator->getUserPublicGroupName($user, false)][$contactUser['label']] = $contactUser;
                }
            }
        }

        uksort($arrayPublicUsers, "strcmp");
//mettere ogni sotto gruppo in ordine alfabetico (non funziona)
//		$keys = array_keys($arrayPublicUsers);
//		foreach($keys as $key)
//		{
//			uksort($arrayPublicUsers[$key], "strcmp");
//		}

        $template->assign('ruoliAdminSearch_users', $arrayPublicUsers);

        $template->assign('ruoliAdminSearch_langAction', "Modifica i diritti nella pagina\n".$canale->getTitolo());
        $template->assign('ruoliAdminSearch_langSearch', "Cerca un altro utente");

        $this->executePlugin('ShowTopic', array('reference' => 'ruoliadmin'));

        return 'default';
    }
}
