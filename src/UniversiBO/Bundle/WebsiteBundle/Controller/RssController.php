<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;
use UniversiBO\Bundle\LegacyBundle\Entity\Canale;


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
        $canale = $canaleRepo->find($idCanale);

        if (!$canale instanceof Canale) {
            throw $this->createNotFoundException('Canale not found');
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
