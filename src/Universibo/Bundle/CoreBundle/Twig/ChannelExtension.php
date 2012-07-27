<?php
namespace Universibo\Bundle\CoreBundle\Twig;

use Universibo\Bundle\CoreBundle\Entity\ChannelRepository;

use Universibo\Bundle\CoreBundle\Entity\Channel;

use Universibo\Bundle\CoreBundle\Routing\ChannelRouter;

class ChannelExtension extends \Twig_Extension
{
    /**
     * Channel router
     *
     * @var ChannelRouter
     */
    private $router;

    /**
     * @var ChannelRepository
     */
    private $channelRepository;

    /**
     * Class constructor
     *
     * @param ChannelRouter $router
     */
    public function __construct(ChannelRouter $router, ChannelRepository $channelRepository)
    {
        $this->router = $router;
        $this->channelRepository = $channelRepository;
    }

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'universibo_core.channel';
    }

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return array (
                'channel_path' =>  new \Twig_Function_Method($this, 'channelPath'),
                'channel_url' =>  new \Twig_Function_Method($this, 'channelUrl'),
                'has_service' =>  new \Twig_Function_Method($this, 'hasService'),
        );
    }

    public function channelPath(Channel $channel)
    {
        return $this->router->getUrl($channel);
    }

    public function channelUrl(Channel $channel)
    {
        return $this->router->getUrl($channel, true);
    }

    public function hasService(Channel $channel, $serviceName)
    {
        foreach ($channel->getServices() as $service) {
            if ($serviceName === $service->getName()) {
                return true;
            }
        }

        return false;
    }
}
