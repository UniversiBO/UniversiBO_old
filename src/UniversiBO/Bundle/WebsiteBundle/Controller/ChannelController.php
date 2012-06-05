<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use UniversiBO\Bundle\LegacyBundle\Entity\Canale;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/channel");
 */
class ChannelController extends Controller
{
    /**
     * @Route("/{id}", requirements={"id" = "\d+"}, name="channel_show")
     * @Template()
     */
    public function showAction($id)
    {
        $acl = $this->get('universibo_legacy.acl');
        $channelRepo = $this->get('universibo_legacy.repository.canale2');

        $scontext = $this->get('security.context');

        $user = $scontext->isGranted('IS_AUTHENTICATED_FULLY') ? $scontext
        ->getToken()->getUser() : null;

        $channel = $channelRepo->find($id);

        if (!$channel instanceof Canale) {
            throw $this->createNotFoundException('Channel not found');
        }

        if (!$acl->canRead($user, $channel)) {
            $response = new Response();
            $response->setStatusCode(403);
            $response->setContent('403 Forbidden');

            return $response;
        }

        return array('channel' => $channel);
    }
}
