<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ChannelMenuController extends Controller
{
    /**
     * @var array
     */
    private $handlers = array();

    public function __construct()
    {
        $that = $this;

        $this->handlers[1] = function () use ($that)
        {
            $repo = $that->get('universibo_legacy.repository.canale');
            $ids = $repo->findManyByType(1);

            return $repo->findManyById($ids);
        };

        $this->handlers[3] = function () use ($that)
        {
            return $that->get('universibo_legacy.repository.facolta')->findAll();
        };
    }

    /**
     * @Template()
     */
    public function indexAction($type, $title, $route='channel_show')
    {
        $allowed = array();

        if (array_key_exists($type, $this->handlers)) {
            $scontext = $this->get('security.context');

            $user = $scontext->isGranted('IS_AUTHENTICATED_FULLY') ? $scontext
                            ->getToken()->getUser() : null;

            $acl = $this->get('universibo_legacy.acl');

            $channels = $this->handlers[$type]();

            foreach ($channels as $key => $item) {
                if ($acl->canRead($user, $item)) {
                    $allowed[] = $item;
                }
            }
        }

        return array('channels' => $allowed, 'title' => $title, 'route' => $route);
    }
}
