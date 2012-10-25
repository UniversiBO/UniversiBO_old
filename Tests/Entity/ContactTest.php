<?php
namespace Universibo\Bundle\CoreBundle\Tests\Entity;

use Universibo\Bundle\CoreBundle\Entity\Contact;
use Universibo\Bundle\CoreBundle\Entity\User;

abstract class ContactTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Contact
     */
    protected $contact;

    public function testTokenAccessors()
    {
        $token = sha1(rand());

        $this->assertSame($this->contact, $this->contact->setVerificationToken($token));
        $this->assertEquals($token, $this->contact->getVerificationToken());
    }

    public function testUserAccessors()
    {
        $user = new User();

        $this->assertSame($this->contact, $this->contact->setUser($user));
        $this->assertEquals($user, $this->contact->getUser());
    }

    public function testVerifiedAtAccessors()
    {
        $verifiedAt = new \DateTime;

        $this->assertSame($this->contact, $this->contact->setVerifiedAt($verifiedAt));
        $this->assertEquals($verifiedAt, $this->contact->getVerifiedAt());
    }

    public function testVerificationSentAtAccessors()
    {
        $verifiedAt = new \DateTime;

        $this->assertSame($this->contact, $this->contact->setVerificationSentAt($verifiedAt));
        $this->assertEquals($verifiedAt, $this->contact->getVerificationSentAt());
    }
}
