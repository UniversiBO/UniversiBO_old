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
    public function generateFeed(Canale $canale, Router $router, $legacy = true)
    {
        $context = $router->getContext();
        $base = $context->getScheme() . '://' . $context->getHost()
                . '/v2.php?do=ShowPermalink&id_notizia=';

        $idCanale = $canale->getIdCanale();

        $feed = new Feed();
        $feed->setTitle($nome = $canale->getNome());
        $feed->setDescription('Feed Canale ' . $nome);
        $feed
                ->setLink(
                        $router
                                ->generate('rss',
                                        array('idCanale' => $idCanale), true));

        $newsRepository = $this->repository;
        $news = $newsRepository->findByCanale($idCanale, 20);
        $news = is_array($news) ? $news : array();

        foreach ($news as $item) {
            $this->newsToEntry($feed, $item, $base, $router, $legacy);
        }

        return $feed;
    }

    private function newsToEntry(Feed $feed, NewsItem $item, $base,
            Router $router, $legacy)
    {
        $entry = $feed->createEntry();
        $entry->setTitle($item->getTitolo());

        $id = $item->getIdNotizia();
        if ($legacy) {
            $link = $base . $id;
        } else {
            $link = $router->generate('news_show', array('id' => $id), true);
        }

        $entry->setLink($link);
        $entry->addAuthor(array('name' => $item->getUsername()));
        $entry->setContent($item->getNotizia());
        $entry->setDateCreated($item->getDataIns());
        $entry->setDateModified($item->getUltimaModifica());

        $feed->addEntry($entry);
    }
}
