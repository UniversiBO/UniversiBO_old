<?php

namespace Universibo\Bundle\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Universibo\Bundle\MainBundle\Entity\SchoolChannel;

/**
 * School controller
 */
class SchoolController extends Controller
{
    /**
     * @Template()
     * @param  string $slug
     * @throws array
     */
    public function indexAction($slug)
    {
        $channelRepo = $this->get('universibo_main.repository.channel');
        $channel = $channelRepo->findOneBySlug($slug);

        if (!$channel instanceof SchoolChannel) {
            throw $this->createNotFoundException('Channel not found or wrong type');
        }

        $school = $channel->getSchool();

        $courseRepo = $this->get('universibo_legacy.repository.cdl');
        $courses = $courseRepo->findBySchool($school);

        return ['school' => $school, 'courses' => $courses];
    }
}
