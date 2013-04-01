<?php

namespace Universibo\Bundle\MainBundle\Tests\Contact;

use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Universibo\Bundle\MainBundle\Contact\ContactService;
use Universibo\Bundle\MainBundle\Entity\Contact;
use Universibo\Bundle\MainBundle\Entity\User;

class ContactServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Contact service to test
     *
     * @var ContactService
     */
    private $service;

    /**
     *
     * @var ObjectManager
     */
    private $objectManager;

    protected function setUp()
    {
        $this->objectManager = $this->getMock('Doctrine\\Common\\Persistence\\ObjectManager');
        $this->service = new ContactService($this->objectManager);
    }

    public function testEnsureContacts()
    {
        $user = new User();

        $email = 'test@example.com';
        $user->setEmail($email);

        $this
            ->objectManager
            ->expects($this->atLeastOnce())
            ->method('merge')
            ->with($this->equalTo($user))
            ->will($this->returnValue($user))
        ;

        $this->service->updateUserEmails($user);

        $this->assertEquals(1, count($user->getContacts()), 'Should have exactly 1 contact');
        $contacts = $user->getContacts();
        $this->assertEquals($email, $contacts[0]->getValue(), 'Email address should match');
        $this->assertSame($user, $contacts[0]->getUser(), 'User should be set');
        $this->assertInstanceOf('DateTime', $contacts[0]->getVerifiedAt());
    }

    public function testAdd()
    {
        $user = new User();

        $email = 'test@example.com';
        $user->setEmail($email);

        $newContact = new Contact();
        $email2 = 'test2@example.com';

        $newContact
            ->setValue($email2)
            ->setVerifiedAt(new DateTime)
            ->setVerificationSentAt(new DateTime)
        ;

        $contacts = $user->getContacts();
        $contacts->add($newContact);

        // expects goes BEFORE call
        $this
            ->objectManager
            ->expects($this->atLeastOnce())
            ->method('merge')
            ->with($this->equalTo($user))
            ->will($this->returnValue($user))
        ;

        $this->service->updateUserEmails($user);
        $this->assertEquals(1, count($contacts), 'User should have 1 contact');

        $this->assertFalse($newContact->isVerified(), 'Should not be verified');
        $this->assertNull($newContact->getVerificationSentAt());
        $this->assertSame($newContact, $contacts[0]);
        $this->assertSame($user, $newContact->getUser(), 'User should be set');

        $this->service->updateUserEmails($user, array($email2));
        $this->assertTrue($newContact->isVerified(), 'Should be verified');
    }

    public function testRemove()
    {
        $user = new User();

        $email = 'test@example.com';
        $user->setEmail($email);

        $newContact = new Contact();
        $email2 = 'test2@example.com';

        $newContact->setValue($email2);

        $contacts = $user->getContacts();
        $contacts->add($newContact);

        // expects goes BEFORE call
        $this
            ->objectManager
            ->expects($this->atLeastOnce())
            ->method('merge')
            ->with($this->equalTo($user))
            ->will($this->returnValue($user))
        ;

        $this->service->updateUserEmails($user);
        $contacts->removeElement($newContact);

        $clonedUser = clone $user;
        $clonedContacts = clone $user->getContacts();
        $clonedContacts->add($newContact);
        $clonedUser->setContacts($clonedContacts);

        $mergedUser = $this->service->updateUserEmails($user);
        $mergedContacts = $mergedUser->getContacts();

        $this->assertEquals(1, count($mergedContacts));
        $contact = $mergedContacts->first();
        $this->assertEquals($email, $contact->getValue());
    }

    public function testDuplicated()
    {
        $user = new User();

        $email = 'test@example.com';
        $user->setEmail($email);

        $newContact = new Contact();
        $email2 = 'test2@example.com';

        $newContact->setValue($email2);

        $contacts = $user->getContacts();
        $contacts->add($newContact);

        $dupContact = clone $newContact;
        $contacts->add($dupContact);

        // expects goes BEFORE call
        $this
            ->objectManager
            ->expects($this->atLeastOnce())
            ->method('merge')
            ->with($this->equalTo($user))
            ->will($this->returnValue($user))
        ;

        $this
            ->objectManager
            ->expects($this->atLeastOnce())
            ->method('refresh')
            ->with($this->equalTo($user))
            ->will($this->returnValue($user))
        ;

        $this
            ->objectManager
            ->expects($this->atLeastOnce())
            ->method('flush')
        ;

        $this
            ->objectManager
            ->expects($this->once())
            ->method('remove')
            ->with($this->isInstanceOf('Universibo\\Bundle\\MainBundle\\Entity\\Contact'))
        ;

        $mergedUser = $this->service->updateUserEmails($user);

        $this->assertEquals(1, count($mergedUser->getContacts()));
        $this->assertEquals($email2, $mergedUser->getContacts()->first()->getValue());
    }

    public function testTokenSentDate()
    {
        $user = new User();

        $email = 'test@example.com';
        $user->setEmail($email);

        $newContact = new Contact();
        $email2 = 'test2@example.com';

        $newContact->setValue($email2);

        $contacts = $user->getContacts();
        $contacts->add($newContact);

        // expects goes BEFORE call
        $this
            ->objectManager
            ->expects($this->atLeastOnce())
            ->method('merge')
            ->with($this->equalTo($user))
            ->will($this->returnValue($user))
        ;

        $now = new DateTime;
        $this->service->updateUserEmails($user, array(), array($email2 => $now));

        $this->assertEquals($now, $newContact->getVerificationSentAt());
    }
}
