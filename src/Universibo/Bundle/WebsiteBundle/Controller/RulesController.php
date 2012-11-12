<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
/**
 */
class RulesController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        $mustAccept = false;

        $context = $this->get('security.context');
        if ($context->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $context->getToken()->getUser();
            $privacyService = $this->get('universibo_legacy.service.privacy');

            $mustAccept = !$privacyService->hasAcceptedPrivacy($user);
        } else {
            $user = null;
        }

        return array('mustAccept' => $mustAccept, 'user' => $user);
    }

    /**
     */
    public function privacyBoxAction()
    {
        $policyRepo = $this->get('universibo_legacy.repository.informativa');
        $current = $policyRepo->findByTime(time());

        $response = $this->render('UniversiboWebsite:Rules:privacy.twig.html', array('policy' => $current));
        $response->setPublic();
        $response->setMaxAge(60);

        return $response;
    }

    public function mainBoxAction()
    {
        $response = $this->render('UniversiboWebsite:Rules:mainBox.twig.html');
        $response->setPublic();
        $response->setMaxAge(3600 * 24);

        return $response;
    }

    public function forumBoxAction()
    {
        $response = $this->render('UniversiboWebsite:Rules:forumBox.twig.html');
        $response->setPublic();
        $response->setMaxAge(3600 * 24);

        return $response;
    }

    /**
     */
    public function acceptAction()
    {
        $context = $this->get('security.context');
        if (!$context->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new NotFoundHttpException();
        }

        $user = $context->getToken()->getUser();

        $flashBag = $this->getRequest()->getSession()->getFlashBag();
        // TODO begin transaction
        $error = false;

        if ('on' !== $this->getRequest()->request->get('accept_check')) {
            $flashBag->add('error', 'Non hai accettato il regolamento!');
            $error = true;
        }

        if (!$error && !$user->isUsernameLocked()) {
            $username = $this->getRequest()->request->get('username');

            if (!preg_match('/^([[:alnum:]àèéìòù \._]{1,25})$/', $username)) {
                $flashBag->add('error', 'Username non valido!');
                $error = true;
            } elseif ($username !== $user->getUsername() &&
                    $this->get('universibo_core.repository.user')
                    ->usernameExists($username)) {
                $flashBag->add('error', $username.': username non disponibile!');
                $error = true;
            } else {
                $user->setUsername($username);
                $user->setUsernameLocked(true);
                $this->get('fos_user.user_manager')->updateUser($user);
            }
        }

        if ($error) {
            return $this->redirect($this->generateUrl('universibo_website_rules'));
        }

        $privacyService = $this->get('universibo_legacy.service.privacy');
        $privacyService->markAccepted($user);

        // TODO commit
        return $this->redirect($this->generateUrl('universibo_legacy_home'));
    }
}
