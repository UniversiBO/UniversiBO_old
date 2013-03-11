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
     * Cookie name
     *
     * @var string
     */
    private $cookieName;

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
        $userId = $this->userDAO->findOrCreate($user);

        $claims = $request->getSession()->get('shibbolethClaims');
        if (!is_array($claims) || !isset($claims['eppn'])) {
            $claims = array('eppn' => '');
        }
        $this->createNewSession($userId, $request, $response, $claims['eppn']);

        $user->setForumId($userId);
    }

    public function logout(ParameterBag $cookies, Response $response)
    {
        $domain = $this->configDAO->getValue('cookie_domain');
        $name = $this->getCookieName();
        $path = $this->configDAO->getValue('cookie_path');
        $secure = $this->configDAO->getValue('cookie_secure');

        $sessionId = $cookies->get($name.'_sid');
        $this->sessionDAO->delete($sessionId);

        foreach (array('u', 'k', 'sid') as $key) {
            $response->headers->setCookie(new Cookie($name.'_'.$key, null, 0,
                    $path, $domain, $secure));
        }

        $this->session->set('phpbb_sid', null);
    }

    public function getSessionId(Request $request)
    {
        $name = $this->getCookieName();

        return $request->cookies->get($name, $this->session->get('phpbb_sid'));
    }

    private function createNewSession($userId, Request $request,
            Response $response, $upn)
    {
        // $request->server returns 127.0.0.1 despite mod_rpaf
        $ip = $_SERVER['REMOTE_ADDR'];
        $userAgent = $request->server->get('HTTP_USER_AGENT');

        $domain = $this->configDAO->getValue('cookie_domain');
        $name = $this->getCookieName();
        $path = $this->configDAO->getValue('cookie_path');
        $secure = $this->configDAO->getValue('cookie_secure');

        $sid = $request->cookies->get($name.'_sid');
        $actualSid = $this->sessionDAO->create($userId, $ip, $userAgent, $sid);

        $response->headers->setCookie(new Cookie($name.'_sid', $actualSid,
                time() + 3600, $path, $domain, $secure));
        $response->headers->setCookie(new Cookie($name.'_u', $userId,
                time() + 3600, $path, $domain, $secure));
        $response->headers->setCookie(new Cookie($name.'_k', '',
                time() + 3600, $path, $domain, $secure));
        $response->headers->setCookie(new Cookie($name.'_shibsession', $upn,
                time() + 3600, $path, $domain, $secure));

        $this->session->set('phpbb_sid', $actualSid);
    }

    /**
     * Gets the cookie name
     *
     * @return string
     */
    private function getCookieName()
    {
        if (null === $this->cookieName) {
            $this->cookieName = $this->configDAO->getValue('cookie_name');
        }

        return $this->cookieName;
    }
}
