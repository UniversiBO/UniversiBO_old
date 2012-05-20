<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/",name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
    
    public function footerAction()
    {
    	$response = $this->render('UniversiBOWebsiteBundle:Default:footer.html.twig');
    	$response->setMaxAge(86400);
    	return $response;
    }
}
