<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ChannelMenuController extends Controller
{

    /**
     * @Template()
     */
    public function indexAction($type, $allowed = array())
    {
        $scontext = $this->get('security.context');

        $user = $scontext->isGranted('IS_AUTHENTICATED_FULLY') ? $scontext
                        ->getToken()->getUser() : null;

        $acl = $this->get('universibo_legacy.acl');
        $router = $this->get('router');

        $channelRepo = $this->get('universibo_legacy.repository.canale2');

        foreach ($channelRepo->findManyByType($type) as $item) {
            if ($acl->canRead($user, $item)) {
                $allowed[] = array('name' => $item->getNome(), 'url' => $item->showMe($router));
            }
        }

        usort($allowed, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return array('links' => $allowed);
    }
}
