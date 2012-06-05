<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

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

    public function headerAction()
    {
        $response = $this->render('UniversiboWebsiteBundle:Default:header.html.twig');
        $response->setSharedMaxAge(3600);

        return $response;
    }

    public function footerAction()
    {
        $response = $this->render('UniversiboWebsiteBundle:Default:footer.html.twig');
        $response->setSharedMaxAge(86400);

        return $response;
    }
}
