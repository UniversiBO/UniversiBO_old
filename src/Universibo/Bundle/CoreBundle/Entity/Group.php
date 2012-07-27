<?php

namespace Universibo\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\Group as BaseGroup;

/**
 * @ORM\Table(name="fos_group")
 * @ORM\Entity(repositoryClass="Universibo\Bundle\CoreBundle\Entity\GroupRepository")
 */
class Group extends BaseGroup
{
    /**
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="groups_id_seq", allocationSize="1", initialValue="1")
     */
    protected $id;
}
