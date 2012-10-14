<?php
namespace Universibo\Bundle\ForumBundle\Security\ForumSession;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\ForumBundle\DAO\ConfigDAOInterface;
use Universibo\Bundle\ForumBundle\DAO\SessionDAOInterface;

class PhpBB3Session implements ForumSessionInterface
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

    public function login(User $user, Response $response)
    {
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

}
