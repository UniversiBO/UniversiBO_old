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
    public function updateUserEmails(User $user, array $verifiedEmails = array(),
            array $sentAtDates = array())
    {
        $contactArray = array();

        foreach ($user->getContacts() as $contact) {
            $contact->setUser($user);
            $contactArray[] = $contact;
        }

        $user = $this->objectManager->merge($user);

        $this->objectManager->flush($user);
        $this->objectManager->refresh($user);

        foreach ($user->getContacts() as $contact) {
            if (!in_array($contact, $contactArray)) {
                $user->getContacts()->removeElement($contact);
                $this->objectManager->remove($contact);
            }
        }

        $contacts = array();
        $user->ensureContact();
        foreach ($user->getContacts() as $contact) {
            $value = $contact->getValue();

            $verified = $value === $user->getEmail() || in_array($value, $verifiedEmails);

            if (!$verified) {
                $contact->setVerificationSentAt(isset($sentAtDates[$value]) ? $sentAtDates[$value] : null);
                $contact->setVerifiedAt(null);
            } elseif (!$contact->isVerified()) {
                $contact->setVerifiedAt(new \DateTime);
            }

            $contacts[$contact->getValue()][] = $contact;
        }

        foreach ($contacts as $value => $list) {
            while (count($list) > 1) {
                $contact = array_pop($list);

                $user->getContacts()->removeElement($contact);
                $this->objectManager->remove($contact);
            }
        }

        $return = $this->objectManager->merge($user);

        $this->objectManager->flush();

        return $return;
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
