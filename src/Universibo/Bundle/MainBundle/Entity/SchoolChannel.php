<?php

namespace Universibo\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Universibo\Bundle\DidacticsBundle\Entity\School;

/**
 * Universibo\Bundle\MainBundle\Entity\Channel
 *
 * @ORM\Table(name="channels_schools")
 * @ORM\Entity(repositoryClass="Universibo\Bundle\MainBundle\Entity\SchoolChannelRepository")
 */
class SchoolChannel extends Channel
{
    /**
     * @ORM\OneToOne(targetEntity="Universibo\Bundle\DidacticsBundle\Entity\School")
     * @ORM\JoinColumn(name="school_id", referencedColumnName="id", nullable=false)
     */
    private $school;

    public function getSchool()
    {
        return $this->school;
    }

    /**
     *
     * @param  School        $school
     * @return SchoolChannel
     */
    public function setSchool(School $school)
    {
        $this->school = $school;

        return $this;
    }

}
