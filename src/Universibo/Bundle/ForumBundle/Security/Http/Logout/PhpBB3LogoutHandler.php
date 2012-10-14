<?php
namespace Universibo\Bundle\ForumBundle\Security\Http\Logout;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Universibo\Bundle\ForumBundle\Security\ForumSession\ForumSessionInterface;

class PhpBB3LogoutHandler implements LogoutHandlerInterface

{
    /**
     *
     * @var ForumSessionInterface
     */
    private $session;

    public function __construct(ForumSessionInterface $session)
    {
        $this->session = $session;
    }

    public function logout(Request $request, Response $response,
            TokenInterface $token)
    {
        $this->session->logout($request->cookies, $response);
    }
}
