<?php
namespace Universibo\Bundle\WebsiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string",length=255)
     * @var string
     */
    private $upn;

    /**
     * @return string
     */
    public function getUpn()
    {
        return $this->upn;
    }

    /**
     * @param string $upn
     */
    public function setUpn($upn)
    {
        $this->upn = $upn;
        
        return $this;
    }
}
