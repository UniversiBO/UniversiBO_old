<?php

namespace Universibo\Bundle\DashboardBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        $requests = $requestRepo->findByApprovedAt(null);

        return ['requests' => $requests];
    }
}
