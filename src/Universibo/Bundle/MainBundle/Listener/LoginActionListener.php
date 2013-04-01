<?php

namespace Universibo\Bundle\MainBundle\Listener;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class LoginActionListener
 * @package Universibo\Bundle\MainBundle\Listener
 */
class LoginActionListener
{
    /**
     * Security context
     *
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * Event dispatcher
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Constructor
     *
     * @param SecurityContextInterface $securityContext
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(SecurityContextInterface $securityContext, EventDispatcherInterface $eventDispatcher)
    {
        $this->securityContext = $securityContext;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->getRequestType() === HttpKernelInterface::SUB_REQUEST) {
            return;
        }

        $request = $event->getRequest();
        if (preg_match('/logout/', $request->getRequestUri())) {
            return;
        }

        $token = $this->securityContext->getToken();
        if (null === $token) {
            return;
        }

        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return;
        }

        $this->eventDispatcher->dispatch('universibo_main.login_action', $event);
    }
}
