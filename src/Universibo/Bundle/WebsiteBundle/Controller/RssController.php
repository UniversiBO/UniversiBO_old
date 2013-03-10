<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Universibo\Bundle\LegacyBundle\Entity\Canale;

class RssController extends Controller
{
    /**
     * Index action
     *
     * @param  Request  $request
     * @param  integer  $idCanale
     * @return Response
     */
    public function indexAction(Request $request, $idCanale)
    {
        $canaleRepo = $this->get('universibo_legacy.repository.canale2');
        $channel = $canaleRepo->find($idCanale);

        if (!$channel instanceof Canale) {
            throw $this->createNotFoundException('Canale not found');
        }

        $response = new Response();
        $response->setMaxAge(60);

        $acl = $this->get('universibo_legacy.acl');
        $public = $acl->canRead(null, $channel);
        if ($public) {
            $response->setPublic();
        }

        if (!$public) {
            $context = $this->get('security.context');

            $user = $context->isGranted('IS_AUTHENTICATED_FULLY') ? $context
            ->getToken()->getUser() : null;

            if (!$acl->canRead($user, $channel)) {
                $response->setStatusCode(403);
                $response->setContent('403 Forbidden');

                return $response;
            }
        }

        $newsRepo = $this->get('universibo_legacy.repository.news.news_item');
        $response->setLastModified($newsRepo->getLastModificationDate($channel));
        if ($response->isNotModified($request)) {
            return $response;
        }

        $generator = $this->get('universibo_website.feed.feed_generator');

        $feed = $generator->generateFeed($channel);

        $response->headers->set('Content-Type', 'application/rss+xml; charset=utf-8');
        $response->setContent($feed->export('rss'));

        return $response;
    }
}
