<?php
namespace Universibo\Bundle\CoreBundle\Channel;

/**
 * Interface for defining channel types
 * 
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
interface ChannelTypeInterface 
{
    /**
     * Gets the channel type name
     * 
     * @return string 
     */
    public function getName();
    
    
    /**
     * Gets the route name for the given type
     * 
     * @param string $type
     * @return string 
     */
    public function getRoute($type);
    
    /**
     * Gets the implemented route types
     * 
     * @return string[] a string array with 'id', 'slug' or both
     */
    public function getRouteTypes();
}