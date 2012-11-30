<?php
namespace Universibo\Bundle\WebsiteBundle\Feed;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Universibo\Bundle\LegacyBundle\Entity\News\NewsItem;

use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\News\DBNewsItemRepository;

use Zend\Feed\Writer\Feed;

/**
 * RSS Feed Generator
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
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
     * @param  Canale $canale
     * @return Feed
     */
    public function generateFeed(Canale $canale, Router $router, $legacy = true)
    {
        $context = $router->getContext();

        $idCanale = $canale->getIdCanale();

        $feed = new Feed();
        $feed->setTitle($nome = $canale->getTitolo());
        $feed->setDescription('Feed ' . $nome);
        $feed
                ->setLink(
                        $router
                                ->generate('rss',
                                        array('idCanale' => $idCanale), true));

        $newsRepository = $this->repository;
        $news = $newsRepository->findByCanale($idCanale, 20);
        $news = is_array($news) ? $news : array();

        foreach ($news as $item) {
            $this->newsToEntry($feed, $item, $router, $legacy);
        }

        return $feed;
    }

    private function newsToEntry(Feed $feed, NewsItem $item,
            Router $router)
    {
        $entry = $feed->createEntry();
        $title = $item->getTitolo();

        // TODO i18n
        $entry->setTitle(empty($title) ? 'Nessun titolo' : $title);

        $id = $item->getIdNotizia();
        $link = $router->generate('universibo_legacy_permalink', array('id_notizia' => $id), true);

        $entry->setLink($link);
        $entry->addAuthor(array('name' => $item->getUsername()));
        $entry->setContent($item->getNotizia());
        $entry->setDateCreated(intval($item->getDataIns()));
        $entry->setDateModified(intval($item->getUltimaModifica()));

        $feed->addEntry($entry);
    }
}
