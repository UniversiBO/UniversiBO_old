<?php
namespace Universibo\Bundle\SSOBundle\Controller;
use Universibo\Bundle\SSOBundle\Service\ShibbolethService;

use Symfony\Component\DependencyInjection\ContainerAware;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class SecurityController extends ContainerAware
{
    public function loginAction()
    {
        return new Response('Please configure mod_shib2');
    }

    public function logoutAction()
    {
        $this->container->get('request')->getSession()->invalidate();
        $this->container->get('security.context')->setToken(null);
        
        $this->container->get('universibo_sso.service.shibboleth')->destroyCookies();

        $url = '/bundles/universibosso/images/greencheck.gif';
        return new RedirectResponse($url);
    }
}
