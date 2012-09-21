<?php
namespace Universibo\Bundle\LegacyBundle\Controller;

use Universibo\Bundle\LegacyBundle\Framework\DefaultReceiver;

use Universibo\Bundle\LegacyBundle\Framework\FrontController;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        $do = $request->get('do', 'ShowHome');
        
        $class = 'Universibo\\Bundle\\LegacyBundle\\Command\\'.$do;
        
        if(!class_exists($class)) {
            throw new NotFoundHttpException(sprintf('Command %s not found', $do));
        }
        
        $base = realpath(__DIR__.'/../../../../..'); 
        $receiver = new DefaultReceiver('main', $base .'/config.xml', $base . '/framework', $base . '/universibo', $this->container);
        $fc = new FrontController($receiver);
        
        return new Response($receiver->main());
    }
}
