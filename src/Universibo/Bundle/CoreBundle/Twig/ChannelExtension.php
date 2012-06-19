<?php
namespace Universibo\Bundle\CoreBundle\Twig;

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
     * Class constructor
     * 
     * @param ChannelRouter $router
     */
    public function __construct(ChannelRouter $router)
    {
        $this->router = $router;
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
                'channel_url' =>  new \Twig_Function_Method($this, 'channelUrl')
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
}