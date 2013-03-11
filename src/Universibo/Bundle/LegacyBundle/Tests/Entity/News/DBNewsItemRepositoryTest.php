<?php
/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
namespace Universibo\Bundle\LegacyBundle\Tests\Entity\News;

use DateTime;
use Universibo\Bundle\LegacyBundle\Entity\News\DBNewsItemRepository;
use Universibo\Bundle\LegacyBundle\Entity\News\NewsItem;
use Universibo\Bundle\LegacyBundle\Tests\Entity\DBRepositoryTest;

class DBNewsItemRepositoryTest extends DBRepositoryTest
{
    /**
     * Repository
     *
     * @var DBNewsItemRepository
     */
    private $repository;

    protected function setUp()
    {
        parent::setUp();

        $container = static::$kernel->getContainer();

        $this->repository = $container->get('universibo_legacy.repository.news.news_item');
        $this->channelRepo = $container->get('universibo_legacy.repository.canale2');
    }

    public function testGetLastModificationDate()
    {
        $now = new DateTime();
        $nowt = $now->getTimestamp();

        $news = new NewsItem(0, 'test', 'body', $nowt, null, $nowt, false, false, 1, 'admin');
        $this->repository->insert($news);
        $this->repository->addToChannel($news,1);

        $this->assertEquals($now, $this->repository->getLastModificationDate($this->channelRepo->find(1)));
    }
}
