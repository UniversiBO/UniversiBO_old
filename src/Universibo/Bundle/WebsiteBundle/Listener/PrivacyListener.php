<?php

namespace Universibo\Bundle\WebsiteBundle\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Universibo\Bundle\LegacyBundle\Entity\InteractiveCommand\StepLogRepository;
use Universibo\Bundle\LegacyBundle\Service\PrivacyService;

/**
 * Privacy policy listener
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPLv2 or later
 */
class PrivacyListener
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var PrivacyService
     */
    private $privacyService;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     *
     * @param SecurityContextInterface $securityContext
     * @param StepLogRepository        $stepLogRepository
     */
    public function __construct(SecurityContextInterface $securityContext,
            PrivacyService $privacyService, RouterInterface $router)
    {
        $this->securityContext = $securityContext;
        $this->privacyService = $privacyService;
        $this->router = $router;
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

        $session = $request->getSession();
        $key = 'privacy_check_result';

        if($session->get($key, false) ||
                !$this->securityContext->getToken() ||
                !$this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
            return;
        }

        $controller = $event->getRequest()->attributes->get('_controller');
        if (preg_match('/privacy/i', $controller)) {
            return;
        }

        $user = $this->securityContext->getToken()->getUser();
        $accepted = $this->privacyService->hasAcceptedPrivacy($user);

        if ($accepted) {
            $session->set($key, true);

            return;
        }

        $response = new Response();
        $response->setStatusCode(302);
        $response->headers->set('Location', $this->router->generate('privacy', array(), true));

        $event->setResponse($response);
    }
}
