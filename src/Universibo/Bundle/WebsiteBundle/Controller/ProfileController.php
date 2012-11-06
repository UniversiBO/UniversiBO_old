<?php

/*
 * Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
 */

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Universibo\Bundle\WebsiteBundle\Form\UserType;

/**
 */
class ProfileController extends Controller
{
    /**
     * @Template()
     */
    public function editAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $form = $this
                ->createForm(new UserType(), $user)
        ;

        $infoEmail = $this->container->getParameter('mailer_info');

        return array('form' => $form->createView(), 'infoEmail' => $infoEmail);
    }

    public function updateAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $request = $this->getRequest();
        $form = $this->getUserForm();

        $verifiedEmails = array();

        foreach ($user->getContacts() as $contact) {
            if ($contact->isVerified()) {
                $verifiedEmails[] = $contact->getValue();
            }
        }

        $contactService = $this->get('universibo_core.contact.service');

        $form->bind($request);
        if ($form->isValid()) {
            $user = $contactService->updateUserEmails($user, $verifiedEmails);

            $verificationService = $this->get('universibo_website.contact.verification');
            $verificationService->sendVerificationEmails($user);

            $this->get('session')->setFlash('notice', 'Il tuo profilo è stato aggiornato.');
        }

        return $this->redirect($this->generateUrl('universibo_website_profile_edit', array(), true));
    }

    private function getUserForm()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        return $this
                        ->createForm(new UserType(), $user)
        ;
    }

}
