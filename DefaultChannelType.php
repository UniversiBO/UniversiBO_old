<?php

namespace Universibo\Bundle\CoreBundle\Channel;

class DefaultChannelType implements ChannelTypeInterface 
{
    private $routes = array (
        'slug' => 'ubcore_channel_slug',
        'id' => 'ubcore_channel_id'
    );

    public function getName() 
    {
        return 'default';
    }

    public function getRoute($type) {
        if(!array_key_exists($type, $this->routes)) {
            throw new \InvalidArgumentException('Unknown route type');
        }
        
        return $this->routes[$type];
    }

    public function getRouteTypes() 
    {
        return array_keys($this->routes);
    }
}