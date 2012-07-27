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
        $this->handlers['single'] = function(ChannelTypeInterface $type, $absolute) use ($router) {
            return $router->generate($type->getRouteName(), array(), $absolute);
        };

        $this->handlers['id'] = function(ChannelTypeInterface $type, $absolute, Channel $channel) use ($router) {
            return $router->generate($type->getRouteName(), array('id' => $channel->getId()), $absolute);
        };

        $this->handlers['slug'] = function(ChannelTypeInterface $type, $absolute, Channel $channel) use ($router) {
            return $router->generate($type->getRouteName(), array('slug' => $channel->getSlug()), $absolute);
        };
    }

    public function getUrl(Channel $channel, $absolute = false)
    {
        if (!array_key_exists($channel->getType(), $this->types)) {
            throw new \InvalidArgumentException('Type not registered');
        }

        $type = $this->types[$channel->getType()];

        return $this->handlers[$type->getRouteType()]($type, $absolute, $channel);
    }

    public function register(ChannelTypeInterface $type)
    {
        if (!in_array($type, $this->types)) {
            $this->types[$type->getName()] = $type;
        }
    }
}
