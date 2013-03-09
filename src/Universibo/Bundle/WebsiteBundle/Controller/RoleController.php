<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Universibo\Bundle\CoreBundle\Entity\User;

/**
 */
class RoleController extends Controller
{
    public function boxAction(array $channel)
    {
        $roleRepo = $this->get('universibo_legacy.repository.ruolo');
        $roles = $roleRepo->findByIdCanale($channel['id_canale']);

        $userRepo = $this->get('universibo_core.repository.user');
        $loggedUser = $this->getUser();
        $loggedUserId = $loggedUser instanceof User ? $loggedUser->getId() : null;

        $viewRoles = array();
        $roleTranslator = $this->get('universibo_legacy.translator.role_name');

        $editAllowed = $this->get('security.context')->isGranted('ROLE_ADMIN');

        foreach ($roles as $role) {
            $user = $userRepo->find($userId = $role->getId());

            if ($role->isReferente() || $role->isModeratore()) {
                $editAllowed = $editAllowed || $loggedUserId === $userId;
                $groupName = $roleTranslator->getUserPublicGroupName($user, false);

                $viewRoles[$groupName][] = array (
                    'userId'     => $user->getId(),
                    'username'   => $user->getUsername(),
                    'referente'  => $role->isReferente(),
                    'moderatore' => $role->isModeratore()
                );
            }
        }

        $response = $this->render('UniversiboWebsiteBundle:Role:box.html.twig', array(
            'display' => count($viewRoles) > 0 || $editAllowed,
            'roles' => $viewRoles,
            'channelId' => $channel['id_canale'],
            'editAllowed' => $editAllowed
        ));

        $response->setSharedMaxAge(30);
        $response->setPublic();

        return $response;
    }
}
