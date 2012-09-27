<?php
namespace Universibo\Bundle\LegacyBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Universibo\Bundle\LegacyBundle\Framework\DefaultReceiver;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class DefaultController extends Controller
{
    public function indexAction()
    {
        $request = $this->getRequest();

        $do = $request->attributes->get('do');
        if (is_null($do)) {
            throw new NotFoundHttpException('Legacy route has been removed, please update');
        }

        $base = realpath(__DIR__.'/../../../../..');
        $receiver = new DefaultReceiver('main', $base .'/config.xml', $base . '/framework', $base . '/universibo', $this->container, $do);

        return new Response($receiver->main());
    }
}
