<?php
namespace Universibo\Bundle\ForumBundle\Security\Http\Logout;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Universibo\Bundle\ForumBundle\DAO\ConfigDAOInterface;

class PhpBB3LogoutHandler implements LogoutHandlerInterface

{
    /**
     * @var ConfigDAOInterface 
     */
    private $configDAO;
    
    
    /**
     * @param \Universibo\Bundle\ForumBundle\DAO\ConfigDAOInterface $configDAO
     */
    public function __construct(ConfigDAOInterface $configDAO)
    {
        $this->configDAO = $configDAO;
    }
    
    public function logout(Request $request, Response $response,
            TokenInterface $token)
    {
        $cookieDomain = $this->configDAO->getValue('cookie_domain');
        $cookieName = $this->configDAO->getValue('cookie_name');
        $cookiePath = $this->configDAO->getValue('cookie_path');
        $cookieSecure = $this->configDAO->getValue('cookie_secure');
    }
}
