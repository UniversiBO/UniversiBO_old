<?php
namespace Universibo\Bundle\ForumBundle\Security\Http\Logout;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Universibo\Bundle\ForumBundle\DAO\ConfigDAOInterface;
use Universibo\Bundle\ForumBundle\DAO\SessionDAOInterface;

class PhpBB3LogoutHandler implements LogoutHandlerInterface

{
    /**
     * @var ConfigDAOInterface
     */
    private $configDAO;
    
    /*
     * @var SessionDAOInterface
     */
    private $sessionDAO;

    /**
     * @param ConfigDAOInterface $configDAO
     */
    public function __construct(ConfigDAOInterface $configDAO,
            SessionDAOInterface $sessionDAO)
    {
        $this->configDAO = $configDAO;
        $this->sessionDAO = $sessionDAO;
    }

    public function logout(Request $request, Response $response,
            TokenInterface $token)
    {
        $domain = $this->configDAO->getValue('cookie_domain');
        $name = $this->configDAO->getValue('cookie_name');
        $path = $this->configDAO->getValue('cookie_path');
        $secure = $this->configDAO->getValue('cookie_secure');

        $sessionId = $request->cookies->get($name.'_sid');
        $this->sessionDAO->delete($sessionId);
        
        foreach (array('u', 'k', 'sid') as $key) {
            $response->headers->setCookie(new Cookie($name.'_'.$key, null, 0,
                    $path, $domain, $secure));
        }
    }
}
