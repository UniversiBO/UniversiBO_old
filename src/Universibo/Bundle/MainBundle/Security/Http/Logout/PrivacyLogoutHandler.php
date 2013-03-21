<?php
/**
 * @copyright (c) 2012, Associazione UniversiBO
 * @license GPLv2
 */

namespace Universibo\Bundle\MainBundle\Security\Http\Logout;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

/**
 * Privacy logout handler
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @link https://github.com/UniversiBO/UniversiBO/issues/220 removes "privacy accepted" action
 */
class PrivacyLogoutHandler implements LogoutHandlerInterface
{
    /**
     * Handles logout
     *
     * @param Request        $request
     * @param Response       $response
     * @param TokenInterface $token
     */
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        $session = $request->getSession();
        $session->set('privacy_check_result', null);
    }
}
