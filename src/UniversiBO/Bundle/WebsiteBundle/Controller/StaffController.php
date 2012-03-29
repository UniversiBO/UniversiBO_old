<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class StaffController extends Controller
{
    /**
     * @Route("/staff",name="staff_index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
