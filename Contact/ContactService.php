<?php

namespace Universibo\Bundle\CoreBundle\Contact;

use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * Contact service
 */
class ContactService
{
    /**
     * Entity manager
     *
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * Class constructor
     *
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Updates user email address
     *
     * @param  User  $user
     * @param  array $oldAddresses
     * @return User
     */
    public function updateUserEmails(User $user)
    {
        $contactArray = array();
        foreach ($user->getContacts() as $contact) {
            $contact->setUser($user);
            $contactArray[] = $contact;
        }

        $user = $this->objectManager->merge($user);
        $user->avoidDuplicatedContacts();
        
        foreach ($user->getContacts() as $contact) {
            if (!in_array($contact, $contactArray)) {
                $user->getContacts()->removeElement($contact);
                $this->objectManager->remove($contact);
            } 
        }

        $user->ensureContact();
        foreach ($user->getContacts() as $contact) {
            if (!$contact->isVerified() && $user->getEmail() === $contact->getValue()) {
                $contact->setVerifiedAt(new DateTime());
            }
        }

        return $this->objectManager->merge($user);
    }

    /**
     * Gets user's emails
     *
     * @param  User  $user
     * @return array
     */
    public function getUserEmails(User $user)
    {
        $emails = array();

        foreach ($user->getContacts() as $contact) {
            if ($contact->isVerified() || !$contact->isVerificationSent()) {
                $emails[] = $contact->getValue();
            }
        }

        if (count($emails) === 0) {
            $emails[] = $user->getEmail();
        }

        return $emails;
    }
}
