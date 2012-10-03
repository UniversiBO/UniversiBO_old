<?php
namespace Universibo\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="Universibo\Bundle\CoreBundle\Entity\UserRepository")
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
    protected $shibUsername;

    /**
     * @ORM\Column(type="string",length=15,nullable=true)
     * @var string
     */
    protected $phone;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    protected $notifications;

    /**
     * @ORM\Column(type="integer", name="groups");
     * @var int
     */
    protected $legacyGroups;

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
     * @param  string                                       $phone
     * @return \Universibo\Bundle\CoreBundle\Entity\User
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
     * @param  integer                                      $notifications
     * @return \Universibo\Bundle\CoreBundle\Entity\User
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;

        return $this;
    }

    /**
     * @deprecated
     * @return number
     */
    public function getLegacyGroups()
    {
        return $this->legacyGroups;
    }

    /**
     * @deprecated
     * @param  int                                          $legacyGroups
     * @return \Universibo\Bundle\CoreBundle\Entity\User
     */
    public function setLegacyGroups($legacyGroups)
    {
        $this->legacyGroups = $legacyGroups;

        return $this;
    }

    /**
     * @deprecated
     * @param  integer $groups
     * @return boolean
     */
    public function isGroupAllowed($groups)
    {
        return (bool) ($groups & $this->legacyGroups);
    }
    
    /**
     * Serializes the user.
     *
     * The serialized data have to contain the fields used by the equals method and the username.
     *
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
            $this->password,
            $this->salt,
            $this->usernameCanonical,
            $this->username,
            $this->expired,
            $this->locked,
            $this->credentialsExpired,
            $this->enabled,
            $this->id,
            $this->shibUsername,
            $this->phone,
            $this->notifications,
            $this->legacyGroups
        ));
    }

    /**
     * Unserializes the user.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        // add a few extra elements in the array to ensure that we have enough keys when unserializing
        // older data which does not include all properties.
        $data = array_merge($data, array_fill(0, 2, null));

        list(
            $this->password,
            $this->salt,
            $this->usernameCanonical,
            $this->username,
            $this->expired,
            $this->locked,
            $this->credentialsExpired,
            $this->enabled,
            $this->id,
            $this->shibUsername,
            $this->phone,
            $this->notifications,
            $this->legacyGroups
        ) = $data;
    }
}
