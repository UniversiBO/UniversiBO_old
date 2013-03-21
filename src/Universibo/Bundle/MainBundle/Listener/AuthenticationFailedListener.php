<?php
namespace Universibo\Bundle\MainBundle\Listener;

use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Routing\RouterInterface;
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
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EngineInterface
     */
    private $templateEngine;

    /**
     * @var string
     */
    private $mailFrom;

    /**
     * @var string
     */
    private $mailFromName;

    /**
     * @var string
     */
    private $devMailTo;

    /**
     * @var string
     */
    private $infoMailTo;

    /**
     *
     * @param Swift_Mailer    $mailer
     * @param LoggerInterface $logger
     * @param RouterInterface $router
     * @param EngineInterface $templateEngine
     */
    public function __construct(Swift_Mailer $mailer, LoggerInterface $logger,
            RouterInterface $router, EngineInterface $templateEngine, $mailFrom,
            $mailFromName, $devMailTo, $infoMailTo)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->router = $router;
        $this->templateEngine = $templateEngine;
        $this->mailFrom = $mailFrom;
        $this->mailFromName = $mailFromName;
        $this->devMailTo = $devMailTo;
        $this->infoMailTo = $infoMailTo;
    }

    /**
     *
     * @param AuthenticationFailedEvent $event
     */
    public function onAuthenticationFailed(AuthenticationFailedEvent $event)
    {
        $claims = $event->getClaims();

        $exception = $event->getException();
        $msg = $exception instanceof \Exception ? $exception->getMessage() : 'none';
        $this->logger->err('Shibboleth Auth failed, eppn: '.$claims['eppn'].
                ', id: '.$claims['idAnagraficaUnica'].', exception: '.$msg);

        $messageDev = Swift_Message::newInstance()
            ->setSubject('Autenticazione Shibboleth Fallita')
            ->setFrom(array($this->mailFrom => $this->mailFromName))
            ->setTo($this->devMailTo)
            ->setBody($this->templateEngine->render('UniversiboMainBundle:Shibboleth:emailDev.txt.twig', array('claims' => $claims)))
        ;

        $messageUser = Swift_Message::newInstance()
            ->setSubject('Attivazione manuale account UniversiBO')
            ->setFrom(array($this->mailFrom => $this->mailFromName))
            ->setTo($claims['eppn'])
            ->setCc($this->infoMailTo)
            ->setBody($this->templateEngine->render('UniversiboMainBundle:Shibboleth:emailUser.txt.twig', array('claims' => $claims)))
        ;

        $this->mailer->send($messageDev);
        $this->mailer->send($messageUser);

        $event->setResponse(new RedirectResponse($this->router->generate('universibo_main_auth_failed')));
    }
}
