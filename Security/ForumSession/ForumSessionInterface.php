<?php
namespace Universibo\Bundle\ForumBundle\Security\ForumSession;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Universibo\Bundle\CoreBundle\Entity\User;

interface ForumSessionInterface
{
    public function login(User $user, Response $response);
    public function logout(ParameterBag $cookies, Response $response);
}
