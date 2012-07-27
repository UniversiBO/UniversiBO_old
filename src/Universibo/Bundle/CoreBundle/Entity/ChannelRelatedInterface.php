<?php
namespace Universibo\Bundle\CoreBundle\Entity;

interface ChannelRelatedInterface
{
    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getChannels();
}
