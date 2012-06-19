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
     * Gets the route name
     * 
     * @return string 
     */
    public function getRouteName();
    
    /**
     * Gets a string with implemented route type
     * Allowed types:
     *   * 'single' (no parameters)
     *   * 'slug' (slug parameter)
     *   * 'id' (id parameter)
     *   
     * @return string
     */
    public function getRouteType();
}