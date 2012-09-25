<?php

namespace Universibo\Bundle\WebsiteBundle\Controller;
use Universibo\Bundle\LegacyBundle\Entity\Collaboratore;

use Universibo\Bundle\WebsiteBundle\Entity\User;

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
        if (!$user instanceof User) {
            throw $this->createNotFoundException('User not found');
        }

        $contact = $cRepo->find($user->getIdUser());

        if (!$contact instanceof Collaboratore) {
            throw $this->createNotFoundException('Staff not found');
        }

        return array('person' => $contact);
    }
}
