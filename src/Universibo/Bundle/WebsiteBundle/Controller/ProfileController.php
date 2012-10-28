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

        $originalContacts = array();

        foreach ($user->getContacts() as $contact) {
            $originalContacts[] = $contact;
        }

        $em = $this->getDoctrine()->getEntityManager();

        $form->bind($request);
        if ($form->isValid()) {
            $userManager = $this->get('fos_user.user_manager');

            foreach ($user->getContacts() as $contact) {
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

            $userManager->updateUser($user);
            $em->flush();

            $this->get('session')->setFlash('notice', 'Il profilo è stato aggiornato');
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
