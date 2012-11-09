<?php

namespace Universibo\Bundle\DidacticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Universibo\Bundle\DidacticsBundle\Entity\Subject
 *
 * @ORM\Table(name="classi_materie")
 * @ORM\Entity(repositoryClass="Universibo\Bundle\DidacticsBundle\Entity\SubjectRepository")
 */
class Subject
{
    /**
     * @var string $code
     * @ORM\Id
     * @ORM\Column(name="cod_materia", type="string", length=5)
     */
    private $code;

    /**
     * @var string $description
     *
     * @ORM\Column(name="desc_materia", type="string", length=200)
     */
    private $description;

    /**
     * Set code
     *
     * @param  string  $code
     * @return Subject
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
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
     * Set description
     *
     * @param  string  $description
     * @return Subject
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
}
