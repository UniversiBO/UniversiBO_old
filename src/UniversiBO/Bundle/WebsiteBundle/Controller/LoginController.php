<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class LoginController extends Controller
{
    /**
     * @Route("/login",name="login")
     */
    public function loginAction()
    {
        return $this->redirect('/index.php?do=Login&symfony=1');
    }
    
    /**
     * @Route("/logout",name="logout")
     */
    public function logoutAction()
    {
        $this->get('request')->getSession()->invalidate();
        return $this->redirect('/index.php?do=Logout&symfony=1');
    }
}
