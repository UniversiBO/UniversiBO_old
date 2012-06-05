<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ChannelMenuController extends Controller
{

    /**
     * @Template()
     */
    public function indexAction($type, $title, $route = 'channel_show')
    {
        $allowed = array();

        $scontext = $this->get('security.context');

        $user = $scontext->isGranted('IS_AUTHENTICATED_FULLY') ? $scontext
                        ->getToken()->getUser() : null;

        $acl = $this->get('universibo_legacy.acl');

        $channelRepo = $this->get('universibo_legacy.repository.canale2');

        foreach ($channelRepo->findManyByType($type) as $item) {
            if ($acl->canRead($user, $item)) {
                $allowed[] = $item;
            }
        }

        return array('channels' => $allowed, 'title' => $title,
                'route' => $route);
    }
}
