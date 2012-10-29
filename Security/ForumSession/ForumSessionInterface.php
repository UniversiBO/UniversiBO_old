<?php
namespace Universibo\Bundle\ForumBundle\Security\ForumSession;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * Login and logout management interface
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
interface ForumSessionInterface
{
    /**
     * Forum login, creates record on forum table if needed
     *
     * @param User     $user
     * @param Request  $request
     * @param Response $response
     */
    public function login(User $user, Request $request, Response $response);

    /**
     * Performs logout on forum
     *
     * @param ParameterBag $cookies
     * @param Response     $response
     */
    public function logout(ParameterBag $cookies, Response $response);

    /**
     * Gets the session id
     *
     * @return string
     */
    public function getSessionId(Request $request);
}
