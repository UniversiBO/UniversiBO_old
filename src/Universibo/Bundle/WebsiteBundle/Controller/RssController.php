<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;
use Universibo\Bundle\LegacyBundle\Entity\Canale;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RssController extends Controller
{
    /**
     * @todo ACL
     * @todo manage Facoltà
     *
     * @Route("/rss/{idCanale}",name="rss",requirements = {"idCanale" = "\d+"})
     */
    public function indexAction($idCanale)
    {
        $canaleRepo = $this->get('universibo_legacy.repository.canale');
        $channel = $canaleRepo->find($idCanale);

        if (!$channel instanceof Canale) {
            throw $this->createNotFoundException('Canale not found');
        }

        $context = $this->get('security.context');

        $acl = $this->get('universibo_legacy.acl');
        $user = $context->isGranted('IS_AUTHENTICATED_FULLY') ? $scontext
        ->getToken()->getUser() : null;

        if (!$acl->canRead($user, $channel)) {
            $response = new Response();
            $response->setStatusCode(403);
            $response->setContent('403 Forbidden');

            return $response;
        }

        $generator = $this->get('universibo_website.feed.feed_generator');

        $feed = $generator
                ->generateFeed($canale, $this->get('router'), true);

        $response = new Response();
        $response->headers
                ->set('Content-Type', 'application/rss+xml; charset=utf-8');
        $response->setContent($feed->export('rss'));

        return $response;
    }
}
