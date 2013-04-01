<?php
namespace Universibo\Bundle\CoreBundle\Tests\Entity;

use DateTime;
use Universibo\Bundle\CoreBundle\Entity\Contact;
use Universibo\Bundle\CoreBundle\Entity\User;

class ContactTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Contact
     */
    private $contact;

    protected function setUp()
    {
        $this->contact = new Contact();
    }

    public function testTokenAccessors()
    {
        $token = sha1(rand());

        $this->assertSame($this->contact, $this->contact->setVerificationToken($token));
        $this->assertEquals($token, $this->contact->getVerificationToken());
    }

    public function testValueAccessors()
    {
        $value = sha1(rand());

        $this->assertSame($this->contact, $this->contact->setValue($value));
        $this->assertEquals($value, $this->contact->getValue());
    }

    public function testUserAccessors()
    {
        $user = new User();

        $this->assertSame($this->contact, $this->contact->setUser($user));
        $this->assertEquals($user, $this->contact->getUser());
    }

    public function testVerifiedAtAccessors()
    {
        $verifiedAt = new DateTime;

        $this->assertSame($this->contact, $this->contact->setVerifiedAt($verifiedAt));
        $this->assertEquals($verifiedAt, $this->contact->getVerifiedAt());
    }

    public function testVerificationSentAtAccessors()
    {
        $verifiedAt = new DateTime;

        $this->assertSame($this->contact, $this->contact->setVerificationSentAt($verifiedAt));
        $this->assertEquals($verifiedAt, $this->contact->getVerificationSentAt());
    }

    public function testVerified()
    {
        $this->assertFalse($this->contact->isVerified());
        $this->contact->setVerifiedAt(new DateTime);
        $this->assertTrue($this->contact->isVerified());
    }
}
