<?php
namespace Universibo\Bundle\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\AdvancedEncoderBundle\Security\Encoder\EncoderAwareInterface;
use FOS\UserBundle\Entity\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="Universibo\Bundle\CoreBundle\Entity\UserRepository")
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser implements EncoderAwareInterface
{
    /**
     * User auto-increment id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * User forum id
     *
     * @ORM\Column(type="integer", name="forum_id")
     */
    protected $forumId;

    /**
     * FOSUserBundle groups
     *
     * @ORM\ManyToMany(targetEntity="Universibo\Bundle\CoreBundle\Entity\Group")
     * @ORM\JoinTable(name="fos_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * FOSUserBundle groups
     *
     * @ORM\ManyToMany(targetEntity="Universibo\Bundle\CoreBundle\Entity\UniboGroup")
     * @ORM\JoinTable(name="fos_user_ismemberof",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="ismemberof_id", referencedColumnName="id")}
     * )
     * @var Collection
     */
    protected $uniboGroups;

    /**
     * FOSAdvancedEncoderBundle encoder name
     *
     * @ORM\Column(type="string",length=15,nullable=true, name="encoder_name")
     * @var string
     */
    protected $encoderName;

    /**
     * Mobile phone number
     *
     * @Assert\Regex("/^\+39[0-9]{9,10}$/")
     * @ORM\Column(type="string",length=15,nullable=true)
     * @var string
     */
    protected $phone;

    /**
     * Notifications level
     *
     * @ORM\Column(type="integer")
     * @var integer
     */
    protected $notifications = 0;

    /**
     * Legacy groups
     *
     * @ORM\Column(type="integer", name="groups");
     * @deprecated
     * @var int
     */
    protected $legacyGroups = 0;

    /**
     * Person this account belongs to
     *
     * @ORM\ManyToOne(targetEntity="Person", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     * @var Person
     */
    protected $person;

    /**
     * If set to false the user will be able to change his own username
     *
     * @ORM\Column(type="integer", type="boolean", name="username_locked")
     * @var boolean
     */
    protected $usernameLocked = true;

    /**
     * Contacts list
     *
     * @ORM\OneToMany(targetEntity="Contact", mappedBy="user",cascade={"persist", "merge"})
     * @var ArrayCollection
     */
    protected $contacts;

    /**
     * @var array
     */
    private static $legacyGroupsMap = array (
        2  => 'ROLE_STUDENT',
        4  => 'ROLE_MODERATOR',
        8  => 'ROLE_TUTOR',
        16 => 'ROLE_PROFESSOR',
        32 => 'ROLE_STAFF',
        64 => 'ROLE_ADMIN'
    );

    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->contacts    = new ArrayCollection();
        $this->groups      = new ArrayCollection();
        $this->uniboGroups = new ArrayCollection();
    }

    /**
     * Mobile phone number getter
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Mobile phone number setter
     *
     * @param  string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Notification level getter
     *
     * @return number
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Notification level setter
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
     * Legacy groups (bitwise) getter
     *
     * @deprecated
     * @return integer
     */
    public function getLegacyGroups()
    {
        return $this->legacyGroups;
    }

    /**
     * Legacy (bitwise) groups setter
     *
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
     * Bitwise operation on groups
     *
     * @deprecated
     * @param  integer $groups
     * @return boolean
     */
    public function isGroupAllowed($groups)
    {
        return (bool) ($groups & $this->legacyGroups);
    }

    /**
     * Person getter
     *
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Person setter
     * @param  Person $person
     * @return User   fluent interface
     */
    public function setPerson(Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * If true the user is allowed to change his own username
     *
     * @return boolean
     */
    public function isUsernameLocked()
    {
        return $this->usernameLocked;
    }

    /**
     * If set to true the user is allowed to change his own username
     * @param  boolean $usernameLocked
     * @return User
     */
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
            $this->usernameLocked
        ) = $data;
    }

    /**
     * Keeps coherence between roles and Legacy Groups
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
     * Ensures that at least one contact is present
     */
    public function ensureContact()
    {
        if (count($this->contacts) == 0) {
            $contact = new Contact();
            $contact->setUser($this);
            $email = $this->getEmail();
            $contact->setValue($email);

            $this->contacts->add($contact);
        }
    }

    /**
     * Ads a role
     *
     * @param  string $role
     * @return User
     */
    public function addRole($role)
    {
        parent::addRole($role);

        $key = array_search($role, self::$legacyGroupsMap);

        if (false !== $key) {
            $this->legacyGroups = $this->legacyGroups | $key;
        }

        return $this;
    }

    /**
     * Removes a role
     *
     * @param  string $role
     * @return User
     */
    public function removeRole($role)
    {
        parent::removeRole($role);

        $key = array_search($role, self::$legacyGroupsMap);

        if (false !== $key) {
            $this->legacyGroups = $this->legacyGroups & ~$key;
        }

        return $this;
    }

    /**
     * Contacts getter
     *
     * @return ArrayCollection
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Contacts setter
     *
     * @param  Collection $contacts
     * @return User
     */
    public function setContacts(Collection $contacts)
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * Unibo Groups
     *
     * @return Collection
     */
    public function getUniboGroups()
    {
        return $this->uniboGroups;
    }

    /**
     * Set unibo groups collection
     *
     * @param  Collection $uniboGroups
     * @return User
     */
    public function setUniboGroups(Collection $uniboGroups)
    {
        $this->uniboGroups = $uniboGroups;

        return $this;
    }

    /**
     * Encoder name getter
     *
     * @return string
     */
    public function getEncoderName()
    {
        return $this->encoderName;
    }

    /**
     * Encoder name setter
     *
     * @param  string $encoderName
     * @return User
     */
    public function setEncoderName($encoderName)
    {
        $this->encoderName = $encoderName;

        return $this;
    }

    /**
     * Gets forum user table id
     *
     * @return integer
     */
    public function getForumId()
    {
        return $this->forumId;
    }

    /**
     * Sets forum user table id
     *
     * @param  integer $forumId
     * @return User
     */
    public function setForumId($forumId)
    {
        $this->forumId = $forumId;

        return $this;
    }

}
