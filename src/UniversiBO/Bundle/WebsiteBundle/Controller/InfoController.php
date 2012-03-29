<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class InfoController extends Controller
{
    /**
     * @Route("/info/rules",name="info_rules")
     * @Template()
     */
    public function rulesAction()
    {
        return array();
    }
}
