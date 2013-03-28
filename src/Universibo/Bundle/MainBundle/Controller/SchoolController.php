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
        $foundCourses = $courseRepo->findBySchool($school);

        $translation = [
            1 => 'CORSI DI LAUREA TRIENNALE',
            2 => 'CORSI DI LAUREA SPECIALISTICA',
            3 => 'CORSI DI LAUREA VECCHIO ORDINAMENTO',
            4 => 'CORSI DI LAUREA TRIENNALE NUOVO ORDINAMENTO',
            5 => 'CORSI DI LAUREA MAGISTRALE',
            6 => 'CORSI DI LAUREA MAGISTRALE A CICLO UNICO',
            7 => 'CORSI DI LAUREA SPECIALISTICA A CICLO UNICO',
        ];

        $courses = array();
        foreach ($foundCourses as $course) {
            $courses[$translation[$course->getCategoriaCdl()]][] = $course;
        }

        foreach ($courses as &$category) {
            uasort($category, function($a, $b){
                return strcasecmp($a->getNome(), $b->getNome());
            });
        }

        return ['school' => $school, 'courses' => $courses];
    }
}
