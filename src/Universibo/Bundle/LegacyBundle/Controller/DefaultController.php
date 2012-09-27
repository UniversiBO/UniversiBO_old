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

        if ($do = $request->query->get('do')) {
            return $this->handleLegacy($do);
        }

        $do = $request->attributes->get('do');

        $base = realpath(__DIR__.'/../../../../..');
        $receiver = new DefaultReceiver('main', $base .'/config.xml', $base . '/framework', $base . '/universibo', $this->container, $do);

        return new Response($receiver->main());
    }

    /**
     * Map legacy urls for google-friendly migration
     *
     * @param  string                $do
     * @throws NotFoundHttpException
     */
    private function handleLegacy($do)
    {
        switch ($do) {
            case 'ShowUser':
                return $this->redirect($this->get('router')->generate('universibo_legacy_user', array('id_utente' => $_GET['id_utente'])));
            case 'ShowError':
                return $this->redirect($this->get('router')->generate('universibo_legacy_error'));

            default:
                throw new NotFoundHttpException('Legacy url not mapped');
        }
    }
}
