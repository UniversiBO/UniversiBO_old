<?php

namespace Universibo\Bundle\ContentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Universibo\Bundle\ContentBundle\Entity\News
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Universibo\Bundle\ContentBundle\Entity\NewsRepository")
 */
class News
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
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=150)
     */
    private $title;

    /**
     * @var datetime $expireDate
     *
     * @ORM\Column(name="expires_at", type="datetime", nullable="true")
     */
    private $expiresAt;

    /**
     * @var text $content
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var boolean $deleted
     *
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted = false;

    /**
     * @var boolean $urgent
     *
     * @ORM\Column(name="urgent", type="boolean")
     */
    private $urgent = false;
    
    /**
     * @ORM\ManyToMany(targetEntity="Universibo\Bundle\CoreBundle\Entity\Channel")
     */
    private $channels;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set expireDate
     *
     * @param datetime $expireDate
     */
    public function setExpiresAt($expireDate)
    {
        $this->expiresAt = $expireDate;
    }

    /**
     * Get expireDate
     *
     * @return datetime 
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return text 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * Get deleted
     *
     * @return boolean 
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set urgent
     *
     * @param boolean $urgent
     */
    public function setUrgent($urgent)
    {
        $this->urgent = $urgent;
    }

    /**
     * Get urgent
     *
     * @return boolean 
     */
    public function getUrgent()
    {
        return $this->urgent;
    }
    
    /**
     * @return ArrayCollection
     */
    public function getChannels()
    {
    	if(is_null($this->channels)) {
    		$this->channels = new ArrayCollection();
    	}
    
    	return $this->channels;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}