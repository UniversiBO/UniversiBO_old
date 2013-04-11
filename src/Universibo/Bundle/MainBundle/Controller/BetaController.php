<?php
/**
 *
 */
namespace Universibo\Bundle\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Universibo\Bundle\MainBundle\Entity\BetaRequest;

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
        $requestRepo = $this->get('universibo_main.repository.beta_request');

        if ($requestRepo->findOneByRequestedBy($this->getUser()) !== null) {
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

        $request = new BetaRequest();
        $request->setRequestedAt(new \DateTime());
        $request->setRequestedBy($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($request);
        $em->flush();

        $url = $this->generateUrl('universibo_main_beta_pending');

        return $this->redirect($url);
    }

    public function approveAction(Request $request)
    {

    }

    /**
     * @Template()
     */
    public function pendingAction()
    {
        $requestRepo = $this->get('universibo_main.repository.beta_request');
        $request = $requestRepo->findOneByRequestedBy($this->getUser());

        if ($request === null) {
            throw $this->createNotFoundException('No request found');
        }

        return ['requestDate' => $request->getRequestedAt()];
    }
}
