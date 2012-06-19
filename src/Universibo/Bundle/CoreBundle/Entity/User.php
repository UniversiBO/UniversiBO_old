<?php

namespace Universibo\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\Group as BaseGroup;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Universibo\Bundle\CoreBundle\Entity\UserRepository")
 */
class User extends BaseGroup
{
    /**
     * 
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="users_id_seq", allocationSize="1", initialValue="1")
     */
    protected $id;
}