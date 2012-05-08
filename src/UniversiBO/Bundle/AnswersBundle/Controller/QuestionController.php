<?php

namespace UniversiBO\Bundle\AnswersBundle\Controller;
use UniversiBO\Bundle\AnswersBundle\Entity\Question;

use UniversiBO\Bundle\AnswersBundle\Form\QuestionType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class QuestionController extends Controller
{
    /**
     * @Route("/question/new")
     * @Template()
     */
    public function newAction()
    {
        $form = $this->createForm(new QuestionType());
        return array('form' => $form->createView());
    }

    /**
     * @Route("/question/create", name="ua_question_create")
     * @Template()
     */
    public function createAction()
    {
        $question = new Question();
        $form = $this->createForm(new QuestionType(), $question);

        $form->bindRequest($this->getRequest());
        
        if ($form->isValid()) {

        }
    }
}
