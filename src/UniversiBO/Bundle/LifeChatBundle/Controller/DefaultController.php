<?php

namespace UniversiBO\Bundle\LifeChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/{id}",requirements={"id" = "\d+"})
     * @Template()
     */
    public function indexAction($id)
    {
        return array('name' => 'World');
    }
}
