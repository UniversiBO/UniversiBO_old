<?php

namespace Universibo\Bundle\WebsiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Universibo\Bundle\WebsiteBundle\Entity\Questionnaire
 *
 * @ORM\Table(name="questionnaires")
 * @ORM\Entity(repositoryClass="Universibo\Bundle\WebsiteBundle\Entity\QuestionnaireRepository")
 */
class Questionnaire
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string $surname
     *
     * @ORM\Column(name="surname", type="string", length=100)
     */
    private $surname;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string $phone
     *
     * @ORM\Column(name="phone", type="string", length=20)
     */
    private $phone;

    /**
     * @var integer $availableTime
     *
     * @ORM\Column(name="availableTime", type="integer")
     */
    private $availableTime;

    /**
     * @var integer $onlineTime
     *
     * @ORM\Column(name="onlineTime", type="integer")
     */
    private $onlineTime;

    /**
     * @var boolean $moderator
     *
     * @ORM\Column(name="moderator", type="boolean")
     */
    private $moderator;

    /**
     * @var boolean $content
     *
     * @ORM\Column(name="content", type="boolean")
     */
    private $content;

    /**
     * @var boolean $test
     *
     * @ORM\Column(name="test", type="boolean")
     */
    private $test;

    /**
     * @var boolean $graphics
     *
     * @ORM\Column(name="graphics", type="boolean")
     */
    private $graphics;

    /**
     * @var boolean $designing
     *
     * @ORM\Column(name="designing", type="boolean")
     */
    private $designing;

    /**
     * @var text $notes
     *
     * @ORM\Column(name="notes", type="text")
     */
    private $notes;

    /**
     * @var string $degreeCourse
     *
     * @ORM\Column(name="degreeCourse", type="string", length=100)
     */
    private $degreeCourse;


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
     * Set surname
     *
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set availableTime
     *
     * @param integer $availableTime
     */
    public function setAvailableTime($availableTime)
    {
        $this->availableTime = $availableTime;
    }

    /**
     * Get availableTime
     *
     * @return integer 
     */
    public function getAvailableTime()
    {
        return $this->availableTime;
    }

    /**
     * Set onlineTime
     *
     * @param integer $onlineTime
     */
    public function setOnlineTime($onlineTime)
    {
        $this->onlineTime = $onlineTime;
    }

    /**
     * Get onlineTime
     *
     * @return integer 
     */
    public function getOnlineTime()
    {
        return $this->onlineTime;
    }

    /**
     * Set moderator
     *
     * @param boolean $moderator
     */
    public function setModerator($moderator)
    {
        $this->moderator = $moderator;
    }

    /**
     * Get moderator
     *
     * @return boolean 
     */
    public function getModerator()
    {
        return $this->moderator;
    }

    /**
     * Set content
     *
     * @param boolean $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return boolean 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set test
     *
     * @param boolean $test
     */
    public function setTest($test)
    {
        $this->test = $test;
    }

    /**
     * Get test
     *
     * @return boolean 
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * Set graphics
     *
     * @param boolean $graphics
     */
    public function setGraphics($graphics)
    {
        $this->graphics = $graphics;
    }

    /**
     * Get graphics
     *
     * @return boolean 
     */
    public function getGraphics()
    {
        return $this->graphics;
    }

    /**
     * Set designing
     *
     * @param boolean $designing
     */
    public function setDesigning($designing)
    {
        $this->designing = $designing;
    }

    /**
     * Get designing
     *
     * @return boolean 
     */
    public function getDesigning()
    {
        return $this->designing;
    }

    /**
     * Set notes
     *
     * @param text $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * Get notes
     *
     * @return text 
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set degreeCourse
     *
     * @param string $degreeCourse
     */
    public function setDegreeCourse($degreeCourse)
    {
        $this->degreeCourse = $degreeCourse;
    }

    /**
     * Get degreeCourse
     *
     * @return string 
     */
    public function getDegreeCourse()
    {
        return $this->degreeCourse;
    }
}