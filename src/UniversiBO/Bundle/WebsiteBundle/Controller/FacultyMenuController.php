<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;
use UniversiBO\Bundle\LegacyBundle\Auth\UniversiBOAcl;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class FacultyMenuController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        $scontext = $this->get('security.context');

        $user = $scontext->isGranted('IS_AUTHENTICATED_FULLY') ? $scontext
                        ->getToken()->getUser() : null;

        $acl = $this->get('universibo_legacy.acl');
        $facolta = $this->get('universibo_legacy.repository.facolta')
                ->findAll();

        $allowed = array();
        foreach ($facolta as $key => $item) {
            if ($acl->canRead($user, $item)) {
                $allowed[] = $item;
            }
        }

        return array('facolta' => $allowed);
    }
}
