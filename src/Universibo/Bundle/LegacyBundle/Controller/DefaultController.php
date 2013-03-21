<?php
namespace Universibo\Bundle\LegacyBundle\Controller;

/**
 * DefaultController class file
 */
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\LegacyBundle\Framework\DefaultReceiver;

/**
 * Default controller: routes all request to legacy framework
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class DefaultController extends Controller
{
    public function indexAction()
    {
        $request = $this->getRequest();

        if ($do = $request->query->get('do', $request->attributes->get('redirect') ? 'ShowHome' : null)) {
            return $this->handleLegacy($do);
        }

        $do = $request->attributes->get('do');

        $base = realpath(__DIR__.'/../../../../..');
        $receiver = new DefaultReceiver('main', $base .'/config.xml', $base . '/framework', $base . '/universibo', $this->container, $do);

        $result = $receiver->main();

        if ($result instanceof Response) {
            return $result;
        }

        return $this->render('UniversiboLegacyBundle:Default:index.html.twig', $result);
    }

    /**
     * Map legacy urls for google-friendly migration
     *
     * @param  string                $do
     * @throws NotFoundHttpException
     */
    private function handleLegacy($do)
    {
        $router = $this->get('router');

        switch ($do) {
            case 'FileDownload':
                return $this->redirect($router->generate('universibo_legacy_file_download', array('id_file' => $_GET['id_file']), true), 301);
            case 'FileShowInfo':
                return $this->redirect($router->generate('universibo_legacy_file', array('id_file' => $_GET['id_file']), true), 301);
            case 'NewsShowCanale':
                  return $this->redirect($router->generate('universibo_legacy_news_show_canale', array('id_canale' => $_GET['id_canale'], 'qta' => $_GET['qta'], 'inizio' => $_GET['inizio']), true), 301);
            case 'Login':
            case 'NewPasswordStudente':
            case 'RecuperaUsernameStudente':
            case 'RegStudente':
                return $this->redirect($router->generate('login', array(), true), 301);
            case 'ShowAccessibility':
                return $this->redirect($router->generate('universibo_legacy_accessibility', array(), true), 301);
            case 'ShowCanale':
                return $this->redirect($router->generate('universibo_legacy_canale', array('id_canale' => $_GET['id_canale']), true), 301);
            case 'ShowCdl':
                return $this->redirect($router->generate('universibo_legacy_cdl', array('id_canale' => $_GET['id_canale']), true), 301);
            case 'ShowCredits':
                return $this->redirect($router->generate('universibo_legacy_credits', array(), true), 301);
            case 'ShowCollaboratore':
                $userRepo = $this->get('universibo_core.repository.user');
                $userId = $this->getRequest()->query->get('id_coll');
                $user = $userRepo->find($userId);
                if ($user === null) {
                    throw new NotFoundHttpException('User not found');
                }

                return $this->redirect($router->generate('universibo_legacy_collaborator', array('username' => $user->getUsername()), true), 301);
            case 'ShowContacts':
                return $this->redirect($router->generate('universibo_legacy_contacts', array(), true), 301);
            case 'ShowContribute':
                return $this->redirect($router->generate('universibo_legacy_contribute', array(), true), 301);
            case 'ShowError':
                return $this->redirect($router->generate('universibo_legacy_error', array(), true), 301);
            case 'ShowFacolta':
                return $this->redirect($router->generate('universibo_legacy_facolta', array('id_canale' => $_GET['id_canale']), true), 301);
            case 'ShowFileInfo':
                return $this->redirect($router->generate('universibo_legacy_file', array('id_file' => $_GET['id_file']), true), 301);
            case 'ShowHelp':
                return $this->redirect($router->generate('universibo_legacy_help', array(), true), 301);
            case 'ShowHelpTopic':
                return $this->redirect($router->generate('universibo_legacy_help_topic', array(), true), 301);
            case 'ShowHome':
                return $this->redirect($router->generate('universibo_legacy_home', array(), true), 301);
            case 'ShowInfoDidattica':
                return $this->redirect($router->generate('universibo_legacy_insegnamento_info', array('id_canale' => $_GET['id_canale']), true), 301);
            case 'ShowInsegnamento':
                return $this->redirect($router->generate('universibo_legacy_insegnamento', array('id_canale' => $_GET['id_canale']), true), 301);
            case 'ShowManifesto':
                  return $this->redirect($router->generate('universibo_legacy_manifesto', array(), true), 301);
            case 'ShowMyUniversiBO':
                  return $this->redirect($router->generate('universibo_legacy_myuniversibo', array(), true), 301);
            case 'ShowPermalink':
                return $this->redirect($router->generate('universibo_legacy_permalink', array('id_notizia' => $_GET['id_notizia']), true), 301);
            case 'ShowRules':
                return $this->redirect($router->generate('universibo_main_rules', array(), true), 301);
            case 'ShowUser':
                return $this->redirect($router->generate('universibo_legacy_user', array('id_utente' => $_GET['id_utente']), true), 301);

            default:
                throw new NotFoundHttpException("Legacy do=$do not mapped");
        }
    }
}
