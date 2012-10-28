<?php
/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
namespace Universibo\Bundle\CoreBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Universibo\Bundle\CoreBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="Universibo\Bundle\CoreBundle\Entity\ContactRepository")
 * @ORM\Table(name="contacts")
 * Class representing a contact (e.g. phone number or email address)
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @var User
     */
    private $user;

    /**
     * @Assert\Email()
     * @ORM\Column(type="string", length=255, nullable=false)
     * @var string
     */
    private $value;

    /**
     * @ORM\Column(type="integer", length=128, nullable=true, name="verification_token")
     * @var string
     */
    private $verificationToken;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="verification_sent_at")
     * @var DateTime
     */
    private $verificationSentAt;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="verified_at")
     * @var DateTime
     */
    private $verifiedAt;

    /**
     * Id getter
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * User getter
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * User setter
     *
     * @param  User    $user
     * @return Contact
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Value getter
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Value setter
     *
     * @param  string  $value
     * @return Contact
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Verification token getter
     *
     * @return string
     */
    public function getVerificationToken()
    {
        return $this->verificationToken;
    }

    /**
     * Verification token setter
     *
     * @param  string  $verificationToken
     * @return Contact
     */
    public function setVerificationToken($verificationToken)
    {
        $this->verificationToken = $verificationToken;

        return $this;
    }

    /**
     * Last verification sending date getter
     *
     * @return DateTime
     */
    public function getVerificationSentAt()
    {
        return $this->verificationSentAt;
    }

    /**
     * Last verification sending date setter
     *
     * @param  DateTime $verificationSentAt
     * @return Contact
     */
    public function setVerificationSentAt(DateTime $verificationSentAt)
    {
        $this->verificationSentAt = $verificationSentAt;

        return $this;
    }

    /**
     * Verification date getter
     *
     * @return DateTime
     */
    public function getVerifiedAt()
    {
        return $this->verifiedAt;
    }

    /**
     * Verification date setter
     *
     * @param  DateTime $verifiedAt
     * @return Contact
     */
    public function setVerifiedAt(DateTime $verifiedAt)
    {
        $this->verifiedAt = $verifiedAt;

        return $this;
    }

    /**
     * Returns true if contact has been verified
     *
     * @return boolean
     */
    public function isVerified()
    {
        return $this->verifiedAt !== null;
    }
}
