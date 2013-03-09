<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 */
class RoleController extends Controller
{
    public function boxAction(array $channel)
    {
        $roleRepo = $this->get('universibo_legacy.repository.ruolo');
        $roles = $roleRepo->findByIdCanale($channel['id_canale']);

        $userRepo = $this->get('universibo_core.repository.user');

        $viewRoles = array();
        $roleTranslator = $this->get('universibo_legacy.translator.role_name');

        foreach ($roles as $role) {

            $user = $userRepo->find($role->getId());
            $groupName = $roleTranslator->getUserPublicGroupName($user, false);

            $viewRoles[$groupName][] = array (
                'userId'     => $user->getId(),
                'username'   => $user->getUsername(),
                'referente'  => $role->isReferente(),
                'moderatore' => $role->isModeratore()
            );
        }

        $response = $this->render('UniversiboWebsiteBundle:Role:box.html.twig', array(
            'display' => count($viewRoles) > 0,
            'roles' => $viewRoles,
            'channelId' => $channel['id_canale']
        ));

        $response->setSharedMaxAge(30);
        $response->setPublic();

        return $response;
    }
}
