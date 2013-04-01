<?php
/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
namespace Universibo\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * User information controller
 */
class UserController extends Controller
{
    /**
     * Tells if the username is available
     *
     * @param  string   $username
     * @return Response
     */
    public function availableAction($username)
    {
        $exists = $this
            ->get('universibo_core.repository.user')
            ->usernameExists($username)
        ;

        $response = new Response();
        $response->headers->set('Content-type', 'application/json; charset=utf-8');
        $response->setContent(json_encode(!$exists));

        return $response;
    }
}
