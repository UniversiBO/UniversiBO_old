<?php

namespace Universibo\Bundle\SSOBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class SecurityController extends Controller
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * Class constructor
     * @param  $templating
     */
    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function loginAction()
    {
        return new Response('Please configure mod_shib2');
    }
}
