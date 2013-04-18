<?php

namespace Universibo\Bundle\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Suggestion
 *
 * @ORM\Table(name="suggestions")
 * @ORM\Entity(repositoryClass="Universibo\Bundle\MainBundle\Entity\SuggestionRepository")
 */
class Suggestion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Author of the comment
     *
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="Universibo\Bundle\MainBundle\Entity\User")
     * @var User
     */
    private $author;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @ORM\Column(name="description", type="string", length=4000, nullable=false)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="would_help", type="boolean")
     */
    private $wouldHelp;

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
     * Author setter
     *
     * @param  User    $author
     * @return Comment
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Gets thes author
     *
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set title
     *
     * @param  string     $title
     * @return Suggestion
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
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
     * Set description
     *
     * @param  string     $description
     * @return Suggestion
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set wouldHelp
     *
     * @param  boolean    $wouldHelp
     * @return Suggestion
     */
    public function setWouldHelp($wouldHelp)
    {
        $this->wouldHelp = $wouldHelp;

        return $this;
    }

    /**
     * Get wouldHelp
     *
     * @return boolean
     */
    public function getWouldHelp()
    {
        return $this->wouldHelp;
    }
}
