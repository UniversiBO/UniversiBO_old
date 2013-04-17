<?php

namespace Universibo\Bundle\MainBundle\Beta;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class BetaMailer
 * @package Universibo\Bundle\MainBundle\Beta
 */
class BetaMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var array
     */
    private $from;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var EngineInterface
     */
    private $engine;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(\Swift_Mailer $mailer, $fromName, $fromAddress, TranslatorInterface $translator,
        EngineInterface $engine, RouterInterface $router)
    {
        $this->mailer = $mailer;
        $this->from = [$fromAddress => $fromName];
        $this->translator = $translator;
        $this->engine = $engine;
        $this->router = $router;
    }

    /**
     * @param BetaApprovedEvent $event
     */
    public function onBetaApproved(BetaApprovedEvent $event)
    {
        $user = $event->getUser();

        $parameters = [
            'username' => $user->getUsername(),
            'login_url' => $this->router->generate('login', [], true)
        ];

        $subject = $this->translator->trans('beta.email.subject');
        $body = $this->engine->render('UniversiboMainBundle:Beta:approval.txt.twig', $parameters);

        $message = \Swift_Message::newInstance($subject, $body);
        $message->setTo($user->getEmail());
        $message->setFrom($this->from);

        $this->mailer->send($message);
    }
}
