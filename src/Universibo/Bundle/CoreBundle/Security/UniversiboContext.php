<?php
namespace Universibo\Bundle\CoreBundle\Security;

use Universibo\Bundle\CoreBundle\Entity\ChannelRelatedInterface;

use Symfony\Component\Security\Core\SecurityContextInterface;

class UniversiboContext
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;
    
    /**
     * Class constructor
     * 
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }
    
    /**
     * @param mixed $attributes
     * @param mixed $object
     * @return boolean
     */
    public function isGranted($attributes, $object = null)
    {
        if($this->securityContext->isGranted($attributes, $object)) {
            return true;
        }
        
        if($object instanceof ChannelRelatedInterface) {
            foreach($object->getChannels() as $channel) {
                if($this->securityContext->isGranted($attributes, $channel)) {
                    return true;
                }
            }
        }
        
        return false;
    }
}