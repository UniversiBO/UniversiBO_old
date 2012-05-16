<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;

use UniversiBO\Bundle\LegacyBundle\Entity\DBCanaleRepository;

use UniversiBO\Bundle\LegacyBundle\Auth\ActiveDirectoryLogin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
