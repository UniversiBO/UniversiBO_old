<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use DOMDocument;
use DOMElement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Universibo\Bundle\LegacyBundle\Auth\LegacyRoles;
use Universibo\Bundle\LegacyBundle\Entity\Canale;

/**
 */
class SitemapController extends Controller
{
    public function indexAction()
    {
        $response = new Response();

        $response->headers->set('Content-type', 'application/xml');

        $document = new DOMDocument('1.0', 'utf-8');

        $root = $document->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'urlset');

        $document->appendChild($root);

        $this->createHome($document, $root);
        $this->createUrl($document, $root, $this->generateUrl('universibo_legacy_manifesto', array(), true));
        $this->createUrl($document, $root, $this->generateUrl('universibo_legacy_contacts', array(), true));
        $this->createUrl($document, $root, $this->generateUrl('universibo_legacy_accessibility', array(), true));
        $this->createUrl($document, $root, $this->generateUrl('universibo_legacy_credits', array(), true));

        $channelRepo = $this->get('universibo_legacy.repository.canale2');
        $channelRouter = $this->get('universibo_legacy.routing.channel');

        $types = array (
            Canale::FACOLTA => 'weekly',
            Canale::CDEFAULT => 'daily',
            Canale::CDL => 'daily',
            Canale::INSEGNAMENTO => 'daily'
        );

        foreach ($types as $type => $changefreq) {
            foreach ($channelRepo->findManyByType($type) as $channel) {
                if ($channel->isGroupAllowed(LegacyRoles::OSPITE)) {
                    $this->createUrl($document, $root, $channelRouter->generate($channel, true), $changefreq);
                }
            }
        }

        $response->setPublic();
        $response->setSharedMaxAge(3600 * 24);
        $response->setContent($document->saveXML());

        return $response;
    }

    private function createHome(DOMDocument $document, DOMElement $root)
    {
        $loc = $this->generateUrl('universibo_legacy_home', array(), true);
        $this->createUrl($document, $root, $loc, 'hourly');
    }

    private function createUrl(DOMDocument $document, DOMElement $root, $loc, $changefreq = 'monthly')
    {
        $url = $document->createElement('url');
        $url->appendChild($document->createElement('loc', $loc));
        $url->appendChild($document->createElement('changefreq', $changefreq));

        $root->appendChild($url);
    }
}
