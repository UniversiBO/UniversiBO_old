<?php
namespace Universibo\Bundle\WebsiteBundle\Listener;

use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Templating\EngineInterface;
use Universibo\Bundle\ShibbolethBundle\Security\Authentication\Event\AuthenticationFailedEvent;

class AuthenticationFailedListener
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EngineInterface
     */
    private $templateEngine;

    public function __construct(Swift_Mailer $mailer, LoggerInterface $logger,
            EngineInterface $templateEngine)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->templateEngine = $templateEngine;
    }

    public function onAuthenticationFailed(AuthenticationFailedEvent $event)
    {
        $claims = $event->getClaims();

        $this->logger->err('Shibboleth Auth failed, eppn: '.$claims['eppn'].
                ', id: '.$claims['idAnagraficaUnica']);

        $messageDev = Swift_Message::newInstance()
            ->setSubject('Autenticazione Fallita')
            ->setFrom('associazione.universibo@unibo.it')
            ->setTo('dev_universibo@mama.ing.unibo.it')
            ->setCc('info_universibo@mama.ing.unibo.it')
            ->setBody($this->templateEngine->render('UniversiboWebsiteBundle:Shibboleth:emailDev.txt.twig', array('claims' => $claims)))
        ;

        $messageUser = Swift_Message::newInstance()
            ->setSubject('Attivazione manuale account UniversiBO')
            ->setFrom('associazione.universibo@unibo.it')
            //->setTo($claims['eppn'])
            ->setCc('info_universibo@mama.ing.unibo.it')
            ->setBody($this->templateEngine->render('UniversiboWebsiteBundle:Shibboleth:emailUser.txt.twig', array('claims' => $claims)))
        ;

        $this->mailer->send($messageDev);
        $this->mailer->send($messageUser);
    }
}
