<?php
namespace Universibo\Bundle\WebsiteBundle\Feed;

use Symfony\Component\Routing\RouterInterface;
use Universibo\Bundle\LegacyBundle\Entity\Canale;
use Universibo\Bundle\LegacyBundle\Entity\News\DBNewsItemRepository;
use Universibo\Bundle\LegacyBundle\Entity\News\NewsItem;
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
     *
     * @var RouterInterface
     */
    private $router;

    /**
     * Class constructor
     *
     * @param DBNewsItemRepository $repository
     * @param RouterInterface      $router
     */
    public function __construct(DBNewsItemRepository $repository, RouterInterface $router)
    {
        $this->repository = $repository;
        $this->router = $router;
    }

    /**
     * Generates a feed from Canale
     * @param  Canale $canale
     * @return Feed
     */
    public function generateFeed(Canale $canale)
    {
        $idCanale = $canale->getIdCanale();

        $feed = new Feed();
        $feed->setTitle($nome = $canale->getTitolo());
        $feed->setDescription('Feed ' . $nome);
        $feed->setLink($this->router->generate('rss', array('idCanale' => $idCanale), true));

        $newsRepository = $this->repository;
        $news = $newsRepository->findByCanale($idCanale, 20);

        foreach ($news as $item) {
            $this->newsToEntry($feed, $item);
        }

        return $feed;
    }

    private function newsToEntry(Feed $feed, NewsItem $item)
    {
        $entry = $feed->createEntry();
        $title = $item->getTitolo();
        $content = $item->getNotizia();

        // TODO i18n
        $entry->setTitle(empty($title) ? 'Nessun titolo' : $title);
        $entry->setContent(empty($content) ? 'Nessun testo' : $content);

        $id = $item->getIdNotizia();
        $link = $this->router->generate('universibo_legacy_permalink', array('id_notizia' => $id), true);

        $entry->setLink($link);
        $entry->addAuthor(array('name' => $item->getUsername()));
        $entry->setDateCreated(intval($item->getDataIns()));
        $entry->setDateModified(intval($item->getUltimaModifica()));

        $feed->addEntry($entry);
    }
}
