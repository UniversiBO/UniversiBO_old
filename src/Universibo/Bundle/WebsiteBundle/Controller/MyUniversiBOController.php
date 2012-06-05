<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/my/universibo");
 */
class MyUniversiBOController extends Controller
{
    /**
     * @Route("/box", name="myuniversibo_box")
     * @Template()
     */
    public function boxAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $rolesRepo = $this->get('universibo_legacy.repository.ruolo');
        $channelRepo = $this->get('universibo_legacy.repository.canale2');
        $router = $this->get('universibo_legacy.routing.channel');

        $roles = $rolesRepo->findByIdUtente($user->getIdUser());

        $displayRoles = array();

        foreach ($roles as $role) {
            if ($role->isMyUniversibo()) {
                $name = $role->getNome();

                $channel = $channelRepo->find($role->getIdCanale());

                if (strlen($name) === 0) {
                    $name = $channel->getNome();
                }

                $displayRoles[] = array ('link' => $router->generate($channel), 'name' => $name);
            }
        }

        return array('roles' => $displayRoles);
    }
}
