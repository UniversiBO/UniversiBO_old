<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity\Notifica;

use Universibo\Bundle\LegacyBundle\Tests\Entity\DoctrineRepositoryTest;

use Universibo\Bundle\LegacyBundle\Entity\Notifica\NotificaItemRepository;


class NotificaItemRepositoryTest extends DoctrineRepositoryTest
{
    /**
     * @var NotificaItemRepository
     */
    private $repo;

    /**
     * 
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->repo = static::$kernel->getContainer()->get('universibo_legacy.repository.notifica.notifica_item');
    }
    
    public function testFind()
    {
        $notification = $this->repo->find(1);
        $this->assertInstanceOf('Universibo\\Bundle\\LegacyBundle\\Entity\\Notifica\\NotificaItem', $notification);
        $this->assertEquals(true, $notification->isUrgente());
        $this->assertEquals('Titolo', $notification->getTitolo());
        $this->assertEquals('Notifica', $notification->getMessaggio());
        $this->assertFalse($notification->isEliminata());
        $this->assertTrue($notification->isUrgente());
    }
    
    public function testFindToSend()
    {
        $toSend = $this->repo->findToSend();
        
        $this->assertArrayHasKey(0, $toSend);
        $this->assertArrayHasKey(1, $toSend);
        
        $this->assertEquals(2, count($toSend));
    }
}
