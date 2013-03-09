<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ChannelMenuController extends Controller
{

    /**
     * Index action
     *
     * @param  integer  $type
     * @param  array    $allowed
     * @return Response
     */
    public function indexAction($type, $allowed = array())
    {
        $scontext = $this->get('security.context');
        $token = $scontext->getToken();

        if ($token !== null) {
            $user = $scontext->isGranted('IS_AUTHENTICATED_FULLY') ?
                    $token->getUser() : null;
        } else {
            $user = null;
        }

        $acl = $this->get('universibo_legacy.acl');
        $router = $this->get('universibo_legacy.routing.channel');

        $channelRepo = $this->get('universibo_legacy.repository.canale2');

        foreach ($channelRepo->findManyByType($type) as $item) {
            if ($acl->canRead($user, $item)) {
                $allowed[] = array('name' => $item->getNome(), 'url' => $router->generate($item));
            }
        }

        usort($allowed, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        $response = $this->render('UniversiboWebsiteBundle:ChannelMenu:index.html.twig', array(
            'links' => $allowed
        ));

        $response->setPrivate();
        $response->setMaxAge(120);

        return $response;
    }
}
