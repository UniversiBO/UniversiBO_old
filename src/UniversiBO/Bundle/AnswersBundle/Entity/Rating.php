<?php

namespace UniversiBO\Bundle\AnswersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UniversiBO\Bundle\AnswersBundle\Entity\Rating
 *
 * @ORM\Table(name="ua_ratings")
 * @ORM\Entity(repositoryClass="UniversiBO\Bundle\AnswersBundle\Entity\RatingRepository")
 */
class Rating
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
     * @var integer $value
     *
     * @ORM\Column(name="value", type="integer")
     */
    private $value;


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
     * Set value
     *
     * @param integer $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return integer 
     */
    public function getValue()
    {
        return $this->value;
    }
}