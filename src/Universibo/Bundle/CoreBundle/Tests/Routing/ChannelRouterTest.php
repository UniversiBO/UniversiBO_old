<?php
namespace Universibo\Bundle\CoreBundle\Tests\Routing;

use Symfony\Component\Routing\RouterInterface;

use Universibo\Bundle\CoreBundle\Channel\ChannelType;
use Universibo\Bundle\CoreBundle\Entity\Channel;
use Universibo\Bundle\CoreBundle\Routing\ChannelRouter;

class ChannelRouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ChannelRouter
     */
    private $channelRouter;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     *
     */
    protected function setUp()
    {
        $this->router = $this->getMock('Symfony\\Component\\Routing\\RouterInterface');
        $this->channelRouter = new ChannelRouter($this->router);
    }

    public function testSingle()
    {
        $this->channelRouter->register(new ChannelType('homepage', 'homepage', 'single'));

        $this
            ->router
            ->expects($this->once())
            ->method('generate')
            ->with($this->equalTo('homepage'))
        ;

        $channel = new Channel();
        $channel->setType('homepage');

        $this->channelRouter->getUrl($channel);
    }
}
