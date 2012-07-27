<?php

namespace Universibo\Bundle\DidacticsBundle\Controller;

use Universibo\Bundle\DidacticsBundle\Entity\Faculty;

use Universibo\Bundle\CoreBundle\Entity\Channel;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/faculty");
 */
class FacultyController extends Controller
{
    /**
     * @Route("/{id}", name="faculty_show")
     * @Template()
     */
    public function showAction($id)
    {
        // TODO acl
        $channelRepo = $this->get('universibo_core.repository.channel');
        $channel = $channelRepo->find($id);

        if (!$channel instanceof Channel) {
            throw $this->createNotFoundException('Channel not found');
        }

        $facultyRepo = $this->get('universibo_didactics.repository.faculty');
        $faculty = $facultyRepo->findOneByChannel($channel);

        if ($faculty instanceof Faculty) {
            throw $this->createNotFoundException('Faculty not found');
        }

        return array('channel' => $channel, 'faculty' => $faculty);
    }
}
