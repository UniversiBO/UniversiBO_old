<?php
namespace Universibo\Bundle\LegacyBundle\Controller;

use Universibo\Bundle\LegacyBundle\Framework\DefaultReceiver;

use Universibo\Bundle\LegacyBundle\Framework\FrontController;

use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class DefaultController extends Controller
{
    public function indexAction()
    {
        $request = $this->getRequest();

        $do = $request->get('do', 'ShowHome');

        $base = realpath(__DIR__.'/../../../../..');
        $receiver = new DefaultReceiver('main', $base .'/config.xml', $base . '/framework', $base . '/universibo', $this->container, $do);
        return new Response($receiver->main());
    }
}
