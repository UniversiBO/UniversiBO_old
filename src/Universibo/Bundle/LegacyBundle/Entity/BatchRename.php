<?php

namespace Universibo\Bundle\LegacyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Universibo\Bundle\LegacyBundle\Entity\BatchRename
 *
 * @ORM\Table(name="batch_rename")
 * @ORM\Entity(repositoryClass="Universibo\Bundle\LegacyBundle\Entity\BatchRenameRepository")
 */
class BatchRename
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="string", length=20)
     */
    private $status;

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
     * Set status
     *
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
