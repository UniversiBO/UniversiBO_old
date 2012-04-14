<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;

use Zend\Feed\Writer\Feed;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class RssController extends Controller
{
    /**
     * @todo ACL
     * @Route("/rss/{idCanale}",name="rss")
     */
    public function indexAction($idCanale)
    {
        $canaleRepo = $this->get('universibo_legacy.repository.canale');
        $canale = $canaleRepo->find($idCanale);
        
        $feed = new Feed();
        $feed->setTitle($nome = $canale->getNome());
        $feed->setDescription('Feed Canale '.$nome);
        $feed->setLink($this->generateUrl('rss', array('idCanale' => $idCanale), true));
        
        $newsRepository = $this->get('universibo_legacy.repository.news.news_item');
        $news = $newsRepository->findByCanale($idCanale);
        $news = is_array($news) ? $news : array();
        
        $context = $this->get('router')->getContext();
        
        $base = $context->getScheme() . '://' . $context->getHost() .'/index.php?do=ShowPermalink&id_notizia=';
        
        foreach($news as $item) {
            $entry = $feed->createEntry();
            $entry->setTitle($item->getTitolo());
            $entry->setLink($base . $item->getIdNotizia());
            $feed->addEntry($entry);
        }
        
        $response = new Response();
        $response->headers->set('Content-Type', 'application/rss+xml; charset=ISO-8859-1');
        $response->setContent($feed->export('rss'));
        
        return $response;
    }
}
