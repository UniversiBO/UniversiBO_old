<?php
namespace Universibo\Bundle\CoreBundle\Entity;

use Symfony\Component\Translation\Loader\ArrayLoader;

interface ChannelRelatedInterface
{
    /**
     * @return Doctrine\Common\Collections\Collection
     */
    public function getChannels();
}
