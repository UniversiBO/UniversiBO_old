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
        $em = $this->getDoctrine()->getEntityManager();

        $originalContacts = array();
        $originalEmails = array();

        foreach ($user->getContacts() as $contact) {
            $originalContacts[] = $contact;
            $originalEmails[] = $contact->getValue();
        }

        $form->bind($request);
        if ($form->isValid()) {
            $userManager = $this->get('fos_user.user_manager');

            $user->avoidDuplicatedContacts();
            $toVerify = array();

            foreach ($user->getContacts() as $contact) {
                $contact->setUser($user);

                $email = $contact->getValue();
                if (!in_array($email, $originalEmails) && $email !== $user->getEmail()) {
                    $contact->setVerificationSentAt(null);
                    $contact->setValidatedAt(null);
                    $toVerify[] = $contact;
                }

                foreach ($originalContacts as $key => $toDel) {
                    if ($toDel->getId() === $contact->getId()) {
                        unset($originalContacts[$key]);
                    }
                }
            }

            foreach ($originalContacts as $contact) {
                $user->getContacts()->removeElement($contact);
                $em->remove($contact);
            }

            foreach ($toVerify as $contact) {
                $contact->setVerifiedAt(null);
            }

            $verificationService = $this->get('universibo_website.contact.verification');
            $verificationService->sendVerificationEmails($user);

            $userManager->updateUser($user);
            $em->flush();

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
