<?php

namespace Universibo\Bundle\LegacyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/privacy")
 */
class PrivacyController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Template()
     */
    public function boxAction()
    {
        $policyRepo = $this->get('universibo_legacy.repository.informativa');
        $current = $policyRepo->findByTime(time());

        return array('policy' => $current);
    }

    /**
     */
    public function acceptAction()
    {
        $context = $this->get('security.context');
        if (!$context->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        }

        $privacyService = $this->get('universibo_legacy.service.privacy');
        $privacyService->markAccepted($context->getToken()->getUser());

        return $this->redirect($this->generateUrl('universibo_legacy_home'));
    }
}
