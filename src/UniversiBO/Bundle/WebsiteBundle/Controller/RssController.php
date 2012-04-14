<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;

use UniversiBO\Bundle\LegacyBundle\Entity\Canale;

use Zend\Feed\Writer\Feed;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class RssController extends Controller
{
    /**
     * @todo ACL
     * @todo manage Facoltà
     * @Route("/rss/{idCanale}",name="rss",requirements = {"idCanale" = "\d+"})
     */
    public function indexAction($idCanale)
    {
        $canaleRepo = $this->get('universibo_legacy.repository.canale');
        $canale = $canaleRepo->find($idCanale);
        
        $feed = new Feed();
        $feed->setTitle($nome = mb_convert_encoding($canale->getNome(), 'utf-8', 'iso-8859-1'));
        $feed->setDescription('Feed Canale '.$nome);
        $feed->setLink($this->generateUrl('rss', array('idCanale' => $idCanale), true));
        
        $newsRepository = $this->get('universibo_legacy.repository.news.news_item');
        $news = $newsRepository->findByCanale($idCanale, 20);
        $news = is_array($news) ? $news : array();
        
        $context = $this->get('router')->getContext();
        
        $base = $context->getScheme() . '://' . $context->getHost() .'/index.php?do=ShowPermalink&id_notizia=';
        
        foreach($news as $item) {
            $entry = $feed->createEntry();
            $entry->setTitle(mb_convert_encoding($item->getTitolo(), 'utf-8','iso-8859-1'));
            $entry->setLink($base . $item->getIdNotizia());
            $feed->addEntry($entry);
        }
        
        $response = new Response();
        $response->headers->set('Content-Type', 'application/rss+xml; charset=utf-8');
        $response->setContent($feed->export('rss'));
        
        return $response;
    }
}
