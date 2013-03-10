<?php
/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use DateTime;
use Universibo\Bundle\LegacyBundle\Entity\News\DBNewsItemRepository;
use Universibo\Bundle\LegacyBundle\Entity\News\NewsItem;

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

        if (!$this->db->unwrap()->isTransactionActive()) {
            $this->db->unwrap()->beginTransaction();
        }
    }

    public function testGetLastModificationDate()
    {
        $now = new DateTime();
        $nowt = $now->getTimestamp();

        $news = new NewsItem(0, 'test', 'body', $nowt, null, $nowt, false, false, 1, 'admin');
        $news->setIdCanali(array(1));
        $this->repository->insert($news);

        $this->assertEquals($now, $this->repository->getLastModificationDate($this->channelRepo->find(1)));
    }
}
