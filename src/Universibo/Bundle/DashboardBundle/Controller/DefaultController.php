<?php

namespace Universibo\Bundle\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('UniversiboDashboardBundle:Default:index.html.twig', array('name' => $name));
    }
}
