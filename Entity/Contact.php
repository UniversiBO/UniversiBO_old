<?php
/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
namespace Universibo\Bundle\CoreBundle\Entity;

use DateTime;
use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * Class representing a contact (e.g. phone number or email address)
 */
class Contact
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $verificationToken;

    /**
     * @var DateTime
     */
    private $verificationSentAt;

    /**
     * @var DateTime
     */
    private $verifiedAt;

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param  User    $user
     * @return Contact
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param  string  $value
     * @return Contact
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getVerificationToken()
    {
        return $this->verificationToken;
    }

    /**
     * @param  string  $verificationToken
     * @return Contact
     */
    public function setVerificationToken($verificationToken)
    {
        $this->verificationToken = $verificationToken;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getVerificationSentAt()
    {
        return $this->verificationSentAt;
    }

    /**
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
     * @return DateTime
     */
    public function getVerifiedAt()
    {
        return $this->verifiedAt;
    }

    /**
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
     * @return boolean
     */
    public function isVerified()
    {
        return $this->verifiedAt !== null;
    }
}
