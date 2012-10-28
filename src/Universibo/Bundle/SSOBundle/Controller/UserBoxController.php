<?php

namespace Universibo\Bundle\SSOBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

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
     * @var SecurityContextInterface
     */
    private $securityContext;

    public function __construct($infoUrl, $logoutUrl, $templating,
            RouterInterface $router, SecurityContextInterface $securityContext)
    {
        $this->infoUrl = $infoUrl;
        $this->logoutUrl = $logoutUrl;
        $this->templating = $templating;
        $this->router = $router;
        $this->securityContext = $securityContext;
    }

    public function indexAction(Request $request)
    {
        $context = $this->securityContext;
        $claims = $request->getSession()->get('shibbolethClaims', array());

        $hasClaims = count($claims) > 0;
        $logged = $context->isGranted('IS_AUTHENTICATED_FULLY');
        $failed = $hasClaims && !$logged;

        $wreply = '?wreply='.urlencode($this->router->generate('universibo_shibboleth_logout', array(), true));
        $logoutUrl = $failed ? $this->logoutUrl.$wreply :
            $this->router->generate('universibo_shibboleth_prelogout');

        if ($hasClaims) {
            $eppn = $claims['eppn'];
        } elseif ($logged) {
            $eppn = $context->getToken()->getUser()->getEmail();
        } else {
            $eppn = '';
        }

        $data = array (
            'eppn' => $eppn,
            'showEppn' => $eppn !== '',
            'logoutUrl' => $logoutUrl
        ) ;

        return $this->templating->renderResponse('UniversiboSSOBundle:UserBox:index.html.twig', $data);
    }
}
