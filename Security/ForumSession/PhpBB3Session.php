<?php
namespace Universibo\Bundle\ForumBundle\Security\ForumSession;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\ForumBundle\DAO\ConfigDAOInterface;
use Universibo\Bundle\ForumBundle\DAO\SessionDAOInterface;
use Universibo\Bundle\ForumBundle\DAO\UserDAOInterface;

class PhpBB3Session implements ForumSessionInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ConfigDAOInterface
     */
    private $configDAO;

    /**
     * @var SessionDAOInterface
     */
    private $sessionDAO;

    /**
     * @var UserDAOInterface
     */
    private $userDAO;

    /**
     * @param ConfigDAOInterface $configDAO
     */
    public function __construct(SessionInterface $session,
            ConfigDAOInterface $configDAO, SessionDAOInterface $sessionDAO,
            UserDAOInterface $userDAO)
    {
        $this->session = $session;
        $this->configDAO = $configDAO;
        $this->sessionDAO = $sessionDAO;
        $this->userDAO = $userDAO;
    }

    public function login(User $user, Request $request, Response $response)
    {
        if (!$this->userDAO->exists($user)) {
            $this->userDAO->create($user);
        }

        $this->createNewSession($user, $response);
    }

    public function logout(ParameterBag $cookies, Response $response)
    {
        $domain = $this->configDAO->getValue('cookie_domain');
        $name = $this->configDAO->getValue('cookie_name');
        $path = $this->configDAO->getValue('cookie_path');
        $secure = $this->configDAO->getValue('cookie_secure');

        $sessionId = $cookies->get($name.'_sid');
        $this->sessionDAO->delete($sessionId);

        foreach (array('u', 'k', 'sid') as $key) {
            $response->headers->setCookie(new Cookie($name.'_'.$key, null, 0,
                    $path, $domain, $secure));
        }
    }

    public function getSessionId()
    {
        return $this->session->get('phpbb_sid');
    }

    private function createNewSession(User $user, Response $response)
    {

    }
}
