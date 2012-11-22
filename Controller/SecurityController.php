<?php

namespace Universibo\Bundle\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Forum security controller
 */
class SecurityController extends Controller
{
    /**
     * This action ensures that the user is logged in the forum
     */
    public function loginAction(Request $request)
    {
        $forumSession = $this->get('universibo_forum.session');
        $target = $request->query->get('wreply', $this->generateUrl('homepage'));
        $response = $this->redirect($target);
         
        $forumSession->login($this->getUser(), $request, $response);
        
        return $response;
    }
}