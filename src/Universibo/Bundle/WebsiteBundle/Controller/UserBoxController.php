<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 */
class UserBoxController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->getUser();

        $roleTranslator = $this->get('universibo_legacy.translator.role_name');

        return array('user' => $user, 'level' => $roleTranslator->translate($user->getRoles()));
    }
}
