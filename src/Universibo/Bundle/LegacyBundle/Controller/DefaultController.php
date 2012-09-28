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
            case 'FileDownload':
                return $this->redirect($this->get('router')->generate('universibo_legacy_file_download', array('id_file' => $_GET['id_file'])), 301);
            case 'FileShowInfo':
                return $this->redirect($this->get('router')->generate('universibo_legacy_file', array('id_file' => $_GET['id_file'])), 301);
            case 'ShowCanale':
                return $this->redirect($this->get('router')->generate('universibo_legacy_canale', array('id_canale' => $_GET['id_canale'])), 301);
            case 'ShowCdl':
                return $this->redirect($this->get('router')->generate('universibo_legacy_cdl', array('id_canale' => $_GET['id_canale'])), 301);
            case 'ShowError':
                return $this->redirect($this->get('router')->generate('universibo_legacy_error'), 301);
            case 'ShowFacolta':
                return $this->redirect($this->get('router')->generate('universibo_legacy_facolta', array('id_canale' => $_GET['id_canale'])), 301);
            case 'ShowFileInfo':
                return $this->redirect($this->get('router')->generate('universibo_legacy_file', array('id_file' => $_GET['id_file'])), 301);
            case 'ShowInfoDidattica':
                return $this->redirect($this->get('router')->generate('universibo_legacy_insegnamento_info', array('id_canale' => $_GET['id_canale'])), 301);
            case 'ShowInsegnamento':
                return $this->redirect($this->get('router')->generate('universibo_legacy_insegnamento', array('id_canale' => $_GET['id_canale'])), 301);
            case 'ShowPermalink':
                return $this->redirect($this->get('router')->generate('universibo_legacy_permalink', array('id_notizia' => $_GET['id_notizia'])), 301);
            case 'ShowUser':
                return $this->redirect($this->get('router')->generate('universibo_legacy_user', array('id_utente' => $_GET['id_utente'])), 301);

            default:
                throw new NotFoundHttpException('Legacy url not mapped');
        }
    }
}
