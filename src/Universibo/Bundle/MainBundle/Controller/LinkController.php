<?php

namespace Universibo\Bundle\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Universibo\Bundle\MainBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\Entity\Canale;

/**
 */
class LinkController extends Controller
{
    public function boxAction($channelId)
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            $acl = $this->get('universibo_legacy.acl');

            $channelObj = $this
                ->get('universibo_legacy.repository.canale')
                ->find($channelId)
            ;

            if (!$channelObj instanceof Canale) {
                throw new NotFoundHttpException('Channel not found');
            }

            $addAllowed = $acl->canDoAction('links.edit', $user, $channelObj);
        } else {
            $addAllowed = false;
        }

        $linkRepo = $this->get('universibo_legacy.repository.links.link');
        $links = $linkRepo->findByChannelId($channelId);

        $response = $this->render('UniversiboMainBundle:Link:box.html.twig', array(
            'addAllowed' => $addAllowed,
            'links' => $links,
            'channelId' => $channelId
        ));

        $response->setMaxAge(10);
        $response->setPrivate();

        return $response;
    }
}
