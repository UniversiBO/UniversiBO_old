<?php

namespace Universibo\Bundle\CoreBundle\Channel;

/**
 * Abstract Channel Type
 * Extending this should be enough in most of cases
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ChannelType implements ChannelTypeInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $routeName;

    /**
     * @var array
     */
    private $routeType;

    /**
     * @param string $name
     * @param string $routeName
     * @param string $routeType
     */
    public function __construct($name, $routeName, $routeType)
    {
        $this->name = $name;
        $this->routeName = $routeName;
        $this->routeType = $routeType;
    }

    /**
     * (non-PHPdoc)
     * @see Universibo\Bundle\CoreBundle\Channel.ChannelTypeInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * (non-PHPdoc)
     * @see Universibo\Bundle\CoreBundle\Channel.ChannelTypeInterface::getRouteName()
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * (non-PHPdoc)
     * @see Universibo\Bundle\CoreBundle\Channel.ChannelTypeInterface::getRouteType()
     */
    public function getRouteType()
    {
        return $this->routeType;
    }
}
