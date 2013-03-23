<?php

namespace Universibo\Bundle\MainBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * Universibo\Bundle\MainBundle\Entity\Channel
 *
 * @ORM\Table(name="channels")
 * @ORM\Entity(repositoryClass="Universibo\Bundle\MainBundle\Entity\ChannelRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"default" = "Channel", "school" = "SchoolChannel"})
 */
abstract class Channel
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
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=50)
     */
    private $type;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var integer $hits
     *
     * @ORM\Column(name="hits", type="integer")
     */
    private $hits;

    /**
     * Updated at
     *
     * @var DateTime
     * @ORM\Column(name="updated_at", type="datetime", nullable = true)
     */
    private $updatedAt;

    /**
     * Legacy groups
     *
     * @ORM\Column(type="integer", name="groups")
     * @deprecated
     * @var int
     */
    private $legacyGroups = 0;

    /**
     * Forum id
     *
     * @ORM\Column(type="integer", name="forum_id", nullable=true)
     * @var int
     */
    private $forumId = 0;

    /**
     * Forum group
     *
     * @ORM\Column(type="integer", name="forum_group_id", nullable=true)
     * @var int
     */
    private $forumGroupId = 0;

    /**
     * @ORM\ManyToMany(targetEntity="ChannelService")
     */
    private $services;

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
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set hits
     *
     * @param integer $hits
     */
    public function setHits($hits)
    {
        $this->hits = $hits;
    }

    /**
     * Get hits
     *
     * @return integer
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * Updated at getter
     *
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Updated at setter
     *
     * @param  DateTime $updatedAt
     * @return Channel
     */
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Legacy groups (bitwise) getter
     *
     * @deprecated
     * @return integer
     */
    public function getLegacyGroups()
    {
        return $this->legacyGroups;
    }

    /**
     * Legacy (bitwise) groups setter
     *
     * @deprecated
     * @param  int  $legacyGroups
     * @return User
     */
    public function setLegacyGroups($legacyGroups)
    {
        $this->legacyGroups = $legacyGroups;

        return $this;
    }

    /**
     * Forum id getter
     *
     * @return integer
     */
    public function getForumId()
    {
        return $this->forumId;
    }

    /**
     * Forum id setter
     *
     * @param  integer $forumId
     * @return Channel
     */
    public function setForumId($forumId)
    {
        $this->forumId = $forumId;

        return $this;
    }

    /**
     * Forum group id getter
     *
     * @return integer
     */
    public function getForumGroupId()
    {
        return $this->forumGroupId;
    }

    /**
     * Forum group id setter
     *
     * @param  integer $forumGroupId
     * @return Channel
     */
    public function setForumGroupId($forumGroupId)
    {
        $this->forumGroupId = $forumGroupId;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getServices()
    {
        if (is_null($this->services)) {
            $this->services = new ArrayCollection();
        }

        return $this->services;
    }

    /**
     * Returns true if channel has service
     *
     * @param  string  $name
     * @return boolean
     */
    public function hasService($name)
    {
        foreach ($this->getServices() as $service) {
            if ($service->getName() === $name) {
                return true;
            }
        }

        return false;
    }
}
