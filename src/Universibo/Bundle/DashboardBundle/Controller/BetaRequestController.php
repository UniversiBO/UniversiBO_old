<?php

namespace Universibo\Bundle\DashboardBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class BetaRequestController
 * @package Universibo\Bundle\DashboardBundle\Controller
 */
class BetaRequestController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        $requestRepo = $this->get('universibo_main.repository.beta_request');
        $requests = $requestRepo->findPending();

        return ['requests' => $requests];
    }

    public function approveAction($id)
    {
        $userRepo = $this->get('universibo_main.repository.user');
        $user = $userRepo->find($id);

        if (null === $user) {
            throw new NotFoundHttpException('User not found');
        }

        $this->get('universibo_main.beta.service')->approve($user, $this->getUser());

        return $this->redirect($this->generateUrl('universibo_dashboard_beta_request'));
    }
}
