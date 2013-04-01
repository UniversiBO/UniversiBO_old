<?php
namespace Universibo\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Universibo\Bundle\CoreBundle\Entity\PersonRepository")
 * @ORM\Table(name="people")
 */
class Person
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", name="unibo_id",nullable=true, unique=true)
     * @var integer
     */
    protected $uniboId;

    /**
     * @ORM\Column(type="string",length=160,nullable=false,name="given_name")
     * @var string
     */
    protected $givenName;

    /**
     * @ORM\Column(type="string",length=160,nullable=true,name="surname")
     * @var string
     */
    protected $surname;

    public function getId()
    {
        return $this->id;
    }

    public function getUniboId()
    {
        return $this->uniboId;
    }

    public function setUniboId($uniboId)
    {
        $this->uniboId = $uniboId;

        return $this;
    }

    public function getGivenName()
    {
        return $this->givenName;
    }

    public function setGivenName($givenName)
    {
        $this->givenName = $givenName;

        return $this;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

}
