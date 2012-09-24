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
     * @ORM\Column(type="string",length=255,nullable=true,name="shib_username")
     * @var string
     */
    private $shibUsername;
    
    /**
     * @ORM\Column(type="string",length=15,nullable=true)
     * @var string
     */
    private $phone;
    
    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $notifications;
    

    /**
     * @return string
     */
    public function getShibUsername()
    {
        return $this->shibUsername;
    }

    /**
     * @param string $shibUsername
     */
    public function setShibUsername($shibUsername)
    {
        $this->shibUsername = $shibUsername;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
    
    /**
     * @param string $phone
     * @return \Universibo\Bundle\WebsiteBundle\Entity\User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        
        return $this;
    }
    
    /**
     * @return number
     */
    public function getNotifications()
    {
        return $this->notifications;
    }
    
    /**
     * 
     * @param integer $notifications
     * @return \Universibo\Bundle\WebsiteBundle\Entity\User
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;
        
        return $this;
    }
}