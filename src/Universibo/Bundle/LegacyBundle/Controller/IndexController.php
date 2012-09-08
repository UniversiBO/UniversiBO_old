<?php
namespace Universibo\Bundle\LegacyBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class IndexController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $request = $this->getRequest();

        $do = $request->get('do', 'ShowHomepage');

        return new Response($do);
    }
}
