<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/privacy")
 */
class PrivacyController extends Controller
{
    /**
     * @Route("",name="privacy")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/accept",name="privacy_accept")
     * @Method("POST")
     */
    public function acceptAction()
    {
    }
}
