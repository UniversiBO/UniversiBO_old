<?php

namespace Universibo\Bundle\SSOBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class MetadataController
{
    public function indexAction()
    {
        return new Response('metadata');
    }
}
