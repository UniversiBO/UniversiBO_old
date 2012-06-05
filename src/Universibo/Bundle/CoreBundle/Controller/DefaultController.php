<?php

namespace Universibo\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function indexAction($name)
    {
        return $this->render('UniversiboCoreBundle:Default:index.html.twig', array('name' => $name));
    }
}
