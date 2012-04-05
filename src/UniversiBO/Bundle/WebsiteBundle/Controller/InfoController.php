<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class InfoController extends Controller
{
    /**
     * @Route("/info/rules",name="info_rules")
     * @Template()
     */
    public function rulesAction()
    {
        $id = 'universibo_legacy.repository.informativa';
        $privacyContent = $this->get($id)->findByTime(time())->getTesto();
        $privacyContent = mb_convert_encoding($privacyContent, 'utf-8', 'iso-8859-1');
        
        return array('privacy' => $privacyContent, 'rules' => file_get_contents($rules));
    } 
}
