<?php

namespace Universibo\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ChannelMenuController extends Controller
{

    /**
     * @Template()
     */
    public function indexAction($type, $title)
    {
        $channelRepo = $this->get('universibo_core.repository.channel');

        // TODO acl
        $channels = $channelRepo->findByType($type);

        return array('channels' => $channels, 'title' => $title);
    }
}
