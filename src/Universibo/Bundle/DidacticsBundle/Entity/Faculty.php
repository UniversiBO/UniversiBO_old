<?php

namespace Universibo\Bundle\DidacticsBundle\Entity;

use Universibo\Bundle\CoreBundle\Entity\Channel;

use Doctrine\ORM\Mapping as ORM;

/**
 * Universibo\Bundle\DidacticsBundle\Entity\Faculty
 *
 * @ORM\Table(name="faculties")
 * @ORM\Entity(repositoryClass="Universibo\Bundle\DidacticsBundle\Entity\FacultyRepository")
 */
class Faculty
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
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=80)
     */
    private $url;
    
    /**
     * 
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
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
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