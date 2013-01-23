<?php
/**
 * @copyright (c) 2002-2013, Associazione UniversiBO
 * @license GPLv2
 */
namespace Universibo\Bundle\WebsiteBundle\Listener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\ForumBundle\Security\ForumSession\ForumSessionInterface;
use Universibo\Bundle\LegacyBundle\Service\PrivacyService;
use Universibo\Bundle\WebsiteBundle\Contact\VerificationService;

/**
 * Privacy policy listener
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>

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
     * @var ForumSessionInterface
     */
    private $forumSession;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * Verification service
     *
     * @var VerificationService
     */
    private $verificationService;

    /**
     * Class constructor
     *
     * @param SecurityContextInterface $securityContext
     * @param RouterInterface          $router
     * @param PrivacyService           $privacyService
     * @param ForumSessionInterface    $forumSession
     * @param VerificationService      $verificationService
     */
    public function __construct(SecurityContextInterface $securityContext,
            RouterInterface $router, PrivacyService $privacyService,
            ForumSessionInterface $forumSession, VerificationService $verificationService)
    {
        $this->securityContext = $securityContext;
        $this->router = $router;
        $this->privacyService = $privacyService;
        $this->forumSession = $forumSession;
        $this->verificationService = $verificationService;
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

        $controller = $request->attributes->get('_controller');
        if (preg_match('/rules/i', $controller) ||
                preg_match('/logout/', $request->getRequestUri())) {
            return;
        }

        $user = $this->securityContext->getToken()->getUser();
        $accepted = $this->privacyService->hasAcceptedPrivacy($user);

        if ($accepted) {
            $session->set($key, true);
            $this->handleForumLogin($user, $request, $event);
            $this->verificationService->sendVerificationEmails($user);

            return;
        }

        $response = new Response();
        $response->setStatusCode(302);
        $response->headers->set('Location', $this->router->generate('universibo_website_rules', array(), true));

        $event->setResponse($response);
    }

    /**
     * Logs the user in and redirects back
     *
     * @param User             $user
     * @param Request          $request
     * @param GetResponseEvent $event
     */
    private function handleForumLogin(User $user, Request $request,
            GetResponseEvent $event)
    {
        $response = new RedirectResponse($request->getRequestUri());
        $this->forumSession->login($user, $request, $response);
        $event->setResponse($response);
    }
}
