<?php
namespace UniversiBO\Bundle\WebsiteBundle\Feed;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use UniversiBO\Bundle\LegacyBundle\Entity\News\NewsItem;

use UniversiBO\Bundle\LegacyBundle\Entity\Canale;
use UniversiBO\Bundle\LegacyBundle\Entity\News\DBNewsItemRepository;

use Zend\Feed\Writer\Feed;

/**
 * RSS Feed Generator
 *
 * @author Davide Bellettini <davide.bellettini@studio.unibo.it>
 * @license GPL v2 or later
 */
class FeedGenerator
{
    /**
     * @var DBNewsItemRepository
     */
    private $repository;

    /**
     * Class constructor
     * @param DBNewsItemRepository $repository
     */
    public function __construct(DBNewsItemRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Generates a feed from Canale
     * @param Canale $canale
     * @return Feed
     */
    public function generateFeed(Canale $canale, Router $router)
    {
        $context = $router->getContext();
        $base = $context->getScheme() . '://' . $context->getHost() .'/index.php?do=ShowPermalink&id_notizia=';

        $idCanale = $canale->getIdCanale();

        $feed = new Feed();
        $feed->setTitle($nome = mb_convert_encoding($canale->getNome(), 'utf-8', 'iso-8859-1'));
        $feed->setDescription('Feed Canale '.$nome);
        $feed->setLink($router->generate('rss', array('idCanale' => $idCanale), true));

        $newsRepository = $this->repository;
        $news = $newsRepository->findByCanale($idCanale, 20);
        $news = is_array($news) ? $news : array();

        foreach($news as $item) {
            $this->newsToEntry($feed, $item, $base);
        }


        return $feed;
    }

    private function newsToEntry(Feed $feed, NewsItem $item, $base)
    {
        $entry = $feed->createEntry();
        $entry->setTitle(mb_convert_encoding($item->getTitolo(), 'utf-8','iso-8859-1'));
        $entry->setLink($base . $item->getIdNotizia());
        $entry->addAuthor(mb_convert_encoding($item->getUsername(), 'utf-8','iso-8859-1'));
        $entry->setContent(mb_convert_encoding($item->getNotizia(), 'utf-8','iso-8859-1'));
        $entry->setDateCreated($item->getDataIns());
        $entry->setDateModified($item->getUltimaModifica());

        $feed->addEntry($entry);
    }
}
