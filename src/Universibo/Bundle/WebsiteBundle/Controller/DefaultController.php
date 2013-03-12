<?php
/**
 * @license GPLv2
 */
namespace Universibo\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Default controller
 */
class DefaultController extends Controller
{
    /**
     * Index action
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('UniversiboWebsiteBundle:Default:index.html.twig');
    }
}
