<?php

namespace Universibo\Bundle\MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Universibo\Bundle\MainBundle\Form\SuggestionType;

/**
 * Class SuggestionController
 * @package Universibo\Bundle\MainBundle\Controller
 */
class SuggestionController extends Controller
{
    /**
     * @Template()
     * @param  Request $request
     * @return array
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(new SuggestionType());

        if ($request->isMethod('POST')) {
            $form->bind($request);

            if ($form->isValid()) {
                $suggestion = $form->getData();

                $suggestion->setAuthor($this->getUser());

                $om = $this->getDoctrine()->getManagerForClass(get_class($suggestion));
                $om->persist($suggestion);
                $om->flush();

                return $this->redirect($this->generateUrl('universibo_main_suggestion_success'));
            }
        }

        return ['form' => $form->createView()];
    }

     /**
      * @Template()
      * @return array
      */
    public function successAction()
    {
        return [];
    }
}
