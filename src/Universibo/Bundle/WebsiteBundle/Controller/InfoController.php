<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route ("/info")
 *
 */
class InfoController extends Controller
{
    /**
     * @Route("/rules",name="info_rules")
     * @Template()
     */
    public function rulesAction()
    {
        $rules = UNIVERSIBO_ROOT . '/universibo/files/regolamento.txt';

        return array('rules' => file_get_contents($rules));
    }

    /**
     * @Route("/contacts", name="info_contacts")
     * @Template()
     */
    public function contactsAction()
    {
        return array();
    }
}
