<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Universibo\Bundle\CoreBundle\Entity\Channel;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        // TODO acl
        $channelRepo = $this->get('universibo_core.repository.channel');

        $channel = $channelRepo->find($id);

        if (!$channel instanceof Channel) {
            throw $this->createNotFoundException('Channel not found');
        }

        return array('channel' => $channel);
    }
}
