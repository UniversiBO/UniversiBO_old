<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class LoginController extends Controller
{
    /**
     * @Route("/login",name="login")
     */
    public function loginAction()
    {
        return $this->redirect('/v2.php?do=Login&symfony=1&referer='.$this->generateUrl('homepage'));
    }

    /**
     * @Route("/logoutAfter",name="logoutAfter")
     */
    public function logoutAfterAction()
    {
        return $this->redirect('/v2.php?do=Logout&symfony=1');
    }
}
