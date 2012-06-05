<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProfileController extends Controller
{
    /**
     * @Route("/profile",name="profile")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
