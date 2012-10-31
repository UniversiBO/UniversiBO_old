<?php

namespace Universibo\Bundle\CoreBundle\Contact;

use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * Contact service
 */
class ContactService
{
    /**
     * Gets user's emails
     * 
     * @param User $user
     * @return array
     */
    public function getUserEmails(User $user)
    {
        $emails = array();
        
        foreach($user->getContacts() as $contact) {
            if($contact->isVerified()) {
                $emails[] = $contact->getEmail();
            }
        }
        
        if(count($emails) === 0) {
            $emails[] = $user->getEmail();
        }
        
        return $emails;
    }
}