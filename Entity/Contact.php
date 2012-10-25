<?php

namespace Universibo\Bundle\CoreBundle\Entity;

use DateTime;
use Universibo\Bundle\CoreBundle\Entity\Contact;
use Universibo\Bundle\CoreBundle\Entity\User;

abstract class Contact
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $verificationToken;

    /**
     * @var DateTime
     */
    private $verifiedAt;

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
     * Gets the verification token
     *
     * @return string
     */
    public function getVerificationToken()
    {
        return $this->verificationToken;
    }

    /**
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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
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
     * @return DateTime
     */
    public function getVerifiedAt()
    {
        return $this->verifiedAt;
    }
}
