<?php

namespace Universibo\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function availableAction($username)
    {
        $exists = $this
            ->get('universibo_core.repository.user')
            ->usernameExists($username)
        ;

        $response = new Response();
        $response->headers->set('Content-type', 'application/json; charset=utf-8');
        $response->setContent(json_encode(!$exists));

        return $response;
    }
}
