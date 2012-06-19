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
     * Gets a string array with implemented route types
     * Allowed types:
     *   * 'single' (no parameters)
     *   * 'slug' (slug parameter)
     *   * 'id' (id parameter)
     *   
     * 'single' type must not be implemented together with 'slug' or 'id'
     * 
     * @return string[] a string array
     */
    public function getRouteTypes();
}