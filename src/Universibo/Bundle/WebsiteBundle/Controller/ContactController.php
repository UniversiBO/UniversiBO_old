<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class ContactController extends Controller
{
    /**
     * @Template()
     */
    public function verifyAction($token)
    {
        $contactRepo = $this->get('universibo_core.repository.contact');
        $contact = $contactRepo->findOneByVerificationToken($token);

        if ($contact === null) {
            throw new NotFoundHttpException('Contact not found');
        }

        if ($this->getUser()->getId() !== $contact->getUser()->getId()) {
            return array('message' => 'Il token non corrisponde all\'utente loggato.');
        }

        if ($contact->isVerified()) {
            return array('message' => 'Il la mail è già stata verificata.');
        }

        $contact->setVerifiedAt(new DateTime());
        $this->getDoctrine()->getEntityManager()->merge($contact);

        return array('message' => 'La tua mail è stata verificata con successo!');
    }

    /**
     * @Template()
     */
    public function cancelAction($token)
    {
        $contactRepo = $this->get('universibo_core.repository.contact');
        $contact = $contactRepo->findOneByVerificationToken($token);

        if ($contact === null) {
            throw new NotFoundHttpException('Contact not found');
        }

        if ($contact->isVerified()) {
            return array('message' => 'La mail è già stata verificata in prececenza.');
        }

        $user = $contact->getUser();

        $user->getContacts()->remove($contact);
        $user->ensureContact();
        $contact->setUser(null);

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($contact);
        $em->merge($user);
        $em->flush();

        return array('La verifica del contatto è stata annullata con successo.');
    }
}
