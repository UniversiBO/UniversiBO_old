<?php
/**
 * @copyright (c) 2002-2013, Associazione UniversiBO
 * @license GPLv2
 */
namespace Universibo\Bundle\MainBundle\Listener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Beta listener
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class BetaListener
{
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(SecurityContextInterface $securityContext, RouterInterface $router)
    {
        $this->securityContext = $securityContext;
        $this->router = $router;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onLoginActionRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $controller = $request->attributes->get('_controller');

        if (preg_match('/beta/i', $controller)) {
            $event->stopPropagation();

            return;
        }

        if (!$this->securityContext->isGranted('ROLE_BETA')) {
            $url = $this->router->generate('universibo_main_beta_index', array(), true);

            $event->setResponse(new RedirectResponse($url));
        }
    }
}
