<?php

namespace Universibo\Bundle\CoreBundle\Entity;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
interface TimestampableInterface
{
    /**
     * @param \DateTime $date
     */
    public function setCreatedAt(\DateTime $date);

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @param \DateTime $date
     */
    public function setUpdatedAt(\DateTime $date);

    /**
     * @return \DateTime
     */
    public function getUpdatedAt();
}
