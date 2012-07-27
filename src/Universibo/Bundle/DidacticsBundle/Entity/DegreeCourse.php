<?php

namespace Universibo\Bundle\DidacticsBundle\Entity;

use Universibo\Bundle\CoreBundle\Entity\Channel;

use Doctrine\ORM\Mapping as ORM;

/**
 * Universibo\Bundle\DidacticsBundle\Entity\DegreeCourse
 *
 * @ORM\Table(name="degree_course")
 * @ORM\Entity(repositoryClass="Universibo\Bundle\DidacticsBundle\Entity\DegreeCourseRepository")
 */
class DegreeCourse
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=4)
     */
    private $code;

    /**
     * @var Channel
     * @ORM\OneToOne(targetEntity="Universibo\Bundle\CoreBundle\Entity\Channel")
     */
    private $channel;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return Channel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * @param Channel $channel
     */
    public function setChannel(Channel $channel)
    {
        $this->channel = $channel;
    }
}
