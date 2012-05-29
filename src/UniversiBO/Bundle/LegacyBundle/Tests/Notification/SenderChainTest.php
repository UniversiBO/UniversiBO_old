<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Notification;

use UniversiBO\Bundle\LegacyBundle\Entity\Notifica\NotificaItem;

use UniversiBO\Bundle\LegacyBundle\Notification\Sender;

use UniversiBO\Bundle\LegacyBundle\Notification\SenderChain;

class SenderChainTest extends \PHPUnit_Framework_TestCase
{
    const INAME = 'UniversiBO\\Bundle\\LegacyBundle\\Notification\\Sender';

    private $chain;

    /**
     *
     */
    protected function setUp()
    {
        $this->chain = new SenderChain();
    }

    public function testImplementsSenderInterface()
    {
        $this->assertTrue($this->chain instanceof Sender, 'Chain should implement Sender interface');
    }

    public function testSupportsFirst()
    {
        $sender = $this->getMock(self::INAME);
        $this->chain->register($sender);

        $notification1 = new NotificaItem(1, 'hello', 'world', time(), false, false, 'mail://hello@world.com');
        $notification2 = new NotificaItem(2, 'hello', 'world', time(), false, false, 'sms://123456789');

        $sender->expects($this->exactly(2))
          ->method('supports')
          ->will($this->onConsecutiveCalls(array(true,false)));

        $this->assertTrue($this->chain->supports($notification1), 'Sender should support mail');
        $this->assertFalse($this->chain->supports($notification2), 'Sender should not support sms');
    }

    public function testRegisterTwice()
    {
        $sender = $this->getMock(self::INAME);
        $this->chain->register($sender);
        $this->chain->register($sender);

        $notification1 = new NotificaItem(1, 'hello', 'world', time(), false, false, 'mail://hello@world.com');

        $sender->expects($this->once())
        ->method('supports')
        ->will($this->returnValue(false));

        $this->chain->supports($notification1);
    }

    public function testUnregister()
    {
        $sender = $this->getMock(self::INAME);
        $this->chain->register($sender);
        $this->chain->unregister($sender);

        $sender->expects($this->never())
        ->method('supports');

        $notification1 = new NotificaItem(1, 'hello', 'world', time(), false, false, 'mail://hello@world.com');

        $this->chain->supports($notification1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testUnregisterUnexisting()
    {
        $sender = $this->getMock(self::INAME);
        $this->chain->unregister($sender);
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testSendUnsupported()
    {
        $notification1 = new NotificaItem(1, 'hello', 'world', time(), false, false, 'mail://hello@world.com');

        $this->chain->send($notification1);
    }

    public function testSend()
    {
        $notification1 = new NotificaItem(1, 'hello', 'world', time(), false, false, 'mail://hello@world.com');

        $sender = $this->getMock(self::INAME);
        $sender->expects($this->once())
        ->method('supports')
        ->with($this->equalTo($notification1))
        ->will($this->returnValue(true));

        $sender->expects($this->once())
        ->method('send')
        ->with($this->equalTo($notification1));

        $this->chain->register($sender);
        $this->chain->send($notification1);
    }
}
