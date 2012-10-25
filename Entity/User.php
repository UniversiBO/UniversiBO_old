<?php
namespace Universibo\Bundle\CoreBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="Universibo\Bundle\CoreBundle\Entity\UserRepository")
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(type="string",length=15,nullable=true,name="member_of")
     * @var string
     */
    protected $memberOf;

    /**
     * @ORM\Column(type="string",length=15,nullable=true)
     * @var string
     */
    protected $phone;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    protected $notifications = 0;

    /**
     * @ORM\Column(type="integer", name="groups");
     * @var int
     */
    protected $legacyGroups;

    /**
     * @ORM\ManyToOne(targetEntity="Person", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     * @var Person
     */
    protected $person;

    /**
     * @ORM\Column(type="integer", type="boolean", name="username_locked")
     * @var boolean
     */
    protected $usernameLocked = true;

    /**
     * @var array
     */
    private static $legacyGroupsMap = array (
        2  => 'ROLE_STUDENT',
        4  => 'ROLE_COLLABORATOR',
        8  => 'ROLE_TUTOR',
        16 => 'ROLE_PROFESSOR',
        32 => 'ROLE_STAFF',
        64 => 'ROLE_ADMIN'
    );

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
     * @param  string $phone
     * @return User
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
     * @param  integer $notifications
     * @return User
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
     * @param  int  $legacyGroups
     * @return User
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

    public function getMemberOf()
    {
        return $this->memberOf;
    }

    public function setMemberOf($memberOf)
    {
        $this->memberOf = $memberOf;

        return $this;
    }

    public function getPerson()
    {
        return $this->person;
    }

    public function setPerson(Person $person)
    {
        $this->person = $person;

        return $this;
    }

    public function isUsernameLocked()
    {
        return $this->usernameLocked;
    }

    public function setUsernameLocked($usernameLocked)
    {
        $this->usernameLocked = $usernameLocked;

        return $this;
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
            $this->legacyGroups,
            $this->memberOf,
            $this->usernameLocked
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
            $this->legacyGroups,
            $this->memberOf,
            $this->usernameLocked
        ) = $data;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateRoles()
    {
        foreach (self::$legacyGroupsMap as $groups => $role) {
            if ($this->getLegacyGroups() & $groups) {
                $this->addRole($role);
            } else {
                $this->removeRole($role);
            }
        }
    }
}
