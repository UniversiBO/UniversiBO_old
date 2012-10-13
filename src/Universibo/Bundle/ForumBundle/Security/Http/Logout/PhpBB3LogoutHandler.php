<?php
namespace Universibo\Bundle\ForumBundle\Security\Http\Logout;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

class PhpBB3LogoutHandler implements LogoutHandlerInterface
{
    public function logout(Request $request, Response $response,
            TokenInterface $token)
    {
    }
}
