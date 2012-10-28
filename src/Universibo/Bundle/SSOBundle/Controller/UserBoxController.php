<?php

namespace Universibo\Bundle\SSOBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class UserBoxController
{
    private $infoUrl;

    private $logoutUrl;

    private $templating;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $afterLogoutRoute;

    public function __construct($infoUrl, $logoutUrl, $templating, RouterInterface $router, $afterLogoutRoute)
    {
        $this->infoUrl = $infoUrl;
        $this->logoutUrl = $logoutUrl;
        $this->templating = $templating;
        $this->router = $router;
        $this->afterLogoutRoute = $afterLogoutRoute;
    }

    public function indexAction(Request $request)
    {
        $claims = $request->getSession()->get('shibbolethClaims', array());
        $logoutUrl = $this->logoutUrl.'?wreply='.$this->router->generate($this->afterLogoutRoute, array(), true);

        return $this->templating->renderResponse('UniversiboSSOBundle:UserBox:index.html.twig', array('infoUrl' => $this->infoUrl, 'logoutUrl' => $logoutUrl, 'claims' => $claims));
    }
}
