<?php
/**
 *
 */
namespace Universibo\Bundle\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class InviteController
 * @package Universibo\Bundle\MainBundle\Controller
 */
class BetaController extends Controller
{
    /**
     * @Template()
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction()
    {
        $context = $this->get('security.context');

        if ($context->isGranted('ROLE_BETA')) {
            $url = $this->generateUrl('universibo_legacy_home');

            return $this->redirect($url);
        }

        $pending = $this->redirectIfPending();
        if ($pending) {
            return $pending;
        }

        return [];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function redirectIfPending()
    {
        $betaService = $this->get('universibo_main.beta.service');

        if ($betaService->find($this->getUser()) !== null) {
            $url = $this->generateUrl('universibo_main_beta_pending');

            return $this->redirect($url);
        }
    }

    public function confirmAction()
    {
        $pending = $this->redirectIfPending();
        if ($pending) {
            return $pending;
        }

        $betaService = $this->get('universibo_main.beta.service');
        $betaService->request($this->getUser());

        $url = $this->generateUrl('universibo_main_beta_pending');

        return $this->redirect($url);
    }

    /**
     * @Template()
     */
    public function pendingAction()
    {
        $betaService = $this->get('universibo_main.beta.service');
        $request = $betaService->find($this->getUser());

        if ($request === null) {
            throw $this->createNotFoundException('No request found');
        }

        return ['requestDate' => $request->getRequestedAt()];
    }
}
