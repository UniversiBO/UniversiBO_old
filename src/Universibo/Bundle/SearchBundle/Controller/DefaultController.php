<?php

namespace Universibo\Bundle\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function indexAction($name)
    {
        return $this->render('UniversiboSearchBundle:Default:index.html.twig', array('name' => $name));
    }
}
