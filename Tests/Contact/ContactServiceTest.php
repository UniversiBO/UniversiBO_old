<?php

namespace Universibo\Bundle\CoreBundle\Tests\Contact;

use Doctrine\Common\Persistence\ObjectManager;
use Universibo\Bundle\CoreBundle\Contact\ContactService;
use Universibo\Bundle\CoreBundle\Entity\Contact;
use Universibo\Bundle\CoreBundle\Entity\User;

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

        $this->service->updateUserEmails($user, array());

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

        $this->service->updateUserEmails($user, array());
        $this->assertEquals(1, count($contacts), 'User should have 1 contact');

        $this->assertSame($newContact, $contacts[0]);
        $this->assertSame($user, $newContact->getUser(), 'User should be set');
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

        $this->service->updateUserEmails($user, array());
        $contacts->removeElement($newContact);

        $clonedUser = clone $user;
        $clonedContacts = clone $user->getContacts();
        $clonedContacts->add($newContact);
        $clonedUser->setContacts($clonedContacts);

        $mergedUser = $this->service->updateUserEmails($user, array($email2));
        $mergedContacts = $mergedUser->getContacts();

        $this->assertEquals(1, count($mergedContacts));
        $contact = $mergedContacts->first();
        $this->assertEquals($email, $contact->getValue());
    }
}
