<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class FacultyMenuController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        $facolta = $this->get('universibo_legacy.repository.facolta')->findAll();

        return array('facolta' => $facolta);
    }
}
