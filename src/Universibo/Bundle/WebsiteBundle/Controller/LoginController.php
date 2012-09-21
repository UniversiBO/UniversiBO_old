<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class LoginController extends Controller
{
    /**
     * @Route("/login",name="login")
     */
    public function loginAction()
    {
        return $this->redirect('/?do=Login&symfony='.$this->generateUrl('homepage'));
    }

    /**
     * @Route("/logoutAfter",name="logoutAfter")
     */
    public function logoutAfterAction()
    {
        return $this->redirect('/?do=Logout&symfony='.$this->generateUrl('homepage'));
    }

    /**
     * Fake implementation
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
    }
}
