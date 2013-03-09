<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Universibo\Bundle\CoreBundle\Entity\User;

/**
 */
class LinkController extends Controller
{
    public function boxAction(array $channel = null)
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            $acl = $this->get('universibo_legacy.acl');

            $channelObj = $this
                ->get('universibo_legacy.repository.canale')
                ->find($channel['id_canale'])
            ;

            $addAllowed = $acl->canDoAction('links.edit', $user, $channelObj);
        } else {
            $addAllowed = false;
        }

        $linkRepo = $this->get('universibo_legacy.repository.links.link');
        $links = $linkRepo->findByChannelId($channel['id_canale']);

        $response = $this->render('UniversiboWebsiteBundle:Link:box.html.twig', array(
            'addAllowed' => $addAllowed,
            'links' => $links,
            'channelId' => $channel['id_canale']
        ));

        $response->setSharedMaxAge(10);
        $response->setPublic();

        return $response;
    }
}
