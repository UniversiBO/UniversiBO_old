<?php

namespace UniversiBO\Bundle\AnswersBundle\Controller;

use UniversiBO\Bundle\AnswersBundle\Form\QuestionType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="answers_home")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
