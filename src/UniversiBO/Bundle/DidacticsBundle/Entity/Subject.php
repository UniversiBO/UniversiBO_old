<?php

namespace UniversiBO\Bundle\DidacticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UniversiBO\Bundle\DidacticsBundle\Entity\Subject
 *
 * @todo nextval('did_subjects_id_seq'::regclass)
 *
 * @ORM\Table(name="did_subjects")
 * @ORM\Entity(repositoryClass="UniversiBO\Bundle\DidacticsBundle\Entity\SubjectRepository")
 */
class Subject
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
     * @ORM\Column(name="code", type="string", length=5, unique=true)
     */
    private $code;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=150, nullable=false)
     */
    private $description;

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
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
