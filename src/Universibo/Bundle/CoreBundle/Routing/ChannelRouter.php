<?php
namespace Universibo\Bundle\CoreBundle\Routing;


use Universibo\Bundle\CoreBundle\Channel\ChannelTypeInterface;

use Universibo\Bundle\CoreBundle\Entity\Channel;

use Symfony\Component\Routing\RouterInterface;

/**
 * Channel router
 */
class ChannelRouter 
{
    /**
     * @var array
     */
    private $types = array();
    
    /**
     * @var array
     */
    private $handlers = array();
    
    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->handlers['single'] = function(ChannelTypeInterface $type) use ($router) {
            $router->generate($type->getRouteName());
        };
        
        $this->handlers['id'] = function(ChannelTypeInterface $type, Channel $channel) use ($router) {
        	$router->generate($type->getRouteName(), array('id' => $channel->getId()));
        };
        
        $this->handlers['slug'] = function(ChannelTypeInterface $type, Channel $channel) use ($router) {
        	$router->generate($type->getRouteName(), array('slug' => $channel->getSlug()));
        };
    }
    
    public function getUrl(Channel $channel)
    {
        if(!array_key_exists($channel->getType(), $this->types)) {
            throw new \InvalidArgumentException('Type not registered');
        }
        
        $type = $this->types[$channel->getType()];
        
        return $this->handlers[$type->getRouteType()]($type, $channel);
    }
    
    public function register(ChannelTypeInterface $type)
    {
        if(!in_array($type, $this->types)) {
            $this->types[$type->getName()] = $type;
        }
    }
}
