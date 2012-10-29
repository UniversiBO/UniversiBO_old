<?php
namespace Universibo\Bundle\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Universibo\Bundle\CoreBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="string",length=15,nullable=true,name="member_of")
     * @var string
     */
    protected $memberOf;

    /**
     * @Assert\Regex("/^\+39[0-9]{9,10}$/")
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
     *
     * @ORM\OneToMany(targetEntity="Contact", mappedBy="user",cascade={"persist", "merge"})
     * @var type 
     */
    protected $contacts;

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
    
    public function __construct()
    {
        parent::__construct();
        
        $this->contacts = new ArrayCollection();
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

        $this->updateRoles();

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
            $this->phone,
            $this->notifications,
            $this->legacyGroups,
            $this->memberOf,
            $this->usernameLocked
        ) = $data;
    }

    /**
     * @ORM\PostLoad
     */
    public function updateRoles()
    {
        foreach (self::$legacyGroupsMap as $groups => $role) {
            if ($this->getLegacyGroups() & $groups) {
                parent::addRole($role);
            } else {
                parent::removeRole($role);
            }
        }
    }
    
    /**
     * @ORM\PostLoad @ORM\PrePersist @ORM\PreUpdate
     */
    public function ensureContact()
    {
        if(count($this->contacts) == 0) {
            $contact = new Contact();
            $contact->setUser($this);
            $email = $this->getEmail();
            $contact->setValue($email);
            
            $this->contacts->add($contact);
        }
    }
    
    /**
     * @ORM\PrePersist @ORM\PreUpdate
     */
    public function avoidDuplicatedContacts()
    {
        $values = array();
        foreach($this->contacts as $contact) {
            $value = $contact->getValue();
            
            if(in_array($value, $values)) {
                $this->contacts->removeElement($contact);
            } else {
                $values[] = $value;                
            }
        }
    }

    public function addRole($role)
    {
        parent::addRole($role);

        $key = array_search($role, self::$legacyGroupsMap);

        if (false !== $key) {
            $this->legacyGroups = $this->legacyGroups | $key;
        }
    }

    public function removeRole($role)
    {
        parent::removeRole($role);

        $key = array_search($role, self::$legacyGroupsMap);

        if (false !== $key) {
            $this->legacyGroups = $this->legacyGroups & ~$key;
        }
    }
    
    public function getContacts()
    {
        return $this->contacts;
    }
    
    public function setContacts($contacts) {
        $this->contacts = $contacts;
        
        return $this;
    }
}
