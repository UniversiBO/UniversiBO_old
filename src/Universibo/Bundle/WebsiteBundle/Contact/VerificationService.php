<?php

namespace Universibo\Bundle\WebsiteBundle\Contact;

use DateTime;
use Doctrine\ORM\EntityManager;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Templating\EngineInterface;
use Universibo\Bundle\CoreBundle\Entity\User;

class VerificationService
{
    /**
     * Mailer
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * Template engine
     *
     * @var EngineInterface
     */
    private $templating;

    /**
     * email => name
     *
     * @var array
     */
    private $mailFrom;

    /**
     * Entity manager
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Class constructor
     *
     * @param Swift_Mailer    $mailer
     * @param string          $mailFrom
     * @param string          $mailFromName
     * @param EngineInterface $templating
     * @param EntityManager   $entityManager
     */
    public function __construct(Swift_Mailer $mailer, $mailFrom, $mailFromName,
            EngineInterface $templating, EntityManager $entityManager)
    {
        $this->mailFrom = array($mailFrom => $mailFromName);
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->entityManager = $entityManager;
    }

    /**
     * Sends verification emails
     * @param User $user
     */
    public function sendVerificationEmails(User $user)
    {
        foreach ($user->getContacts() as $contact) {
            if (!$contact->isVerified() && !$contact->isTokenSent() ) {
                if ($contact->getValue() === $user->getEmail()) {
                    $contact->setValidatedAt(new DateTime());
                } else {
                    $bytes = openssl_random_pseudo_bytes(32);
                    $contact->setVerificationToken(sha1($bytes));
                    $contact->setVerificationSentAt(new DateTime());
                    $contact->setVerifiedAt(null);

                    $body = $this->templating->render('UniversiboWebsiteBundle:Profile:emailValidation.txt.twig',
                            array('user' => $user, 'contact' => $contact));

                    $message = Swift_Message::newInstance()
                        ->setTo($contact->getValue())
                        ->setSubject('[UniversiBO] Verifica Indirizzo E-mail')
                        ->setBody($body)
                        ->setFrom($this->mailFrom)
                    ;

                    $this->entityManager->merge($contact);
                    $this->mailer->send($message);

                }
            }
        }
    }
}
