<?php

namespace UniversiBO\Bundle\WebsiteBundle\Controller;
use UniversiBO\Bundle\LegacyBundle\Entity\DBCollaboratoreRepository;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/staff")
 * @author davide
 *
 */
class StaffController extends Controller
{
    /**
     * @Route("",name="staff_index")
     * @Template()
     */
    public function indexAction()
    {
        $repo = $this->get('universibo_legacy.repository.collaboratore');
        return array('staff' => $repo->findAll(true));
    }

    /**
     * @Route("/{username}",name="staff_show")
     * @Template()
     */
    public function showAction($username)
    {
        $uRepo = $this->get('universibo_legacy.repository.user');
        $cRepo = $this->get('universibo_legacy.repository.collaboratore');

        $user = $uRepo->findByUsername($username);

        return array('person' => $cRepo->find($user->getIdUser()));
    }
}
