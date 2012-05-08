<?php

namespace UniversiBO\Bundle\AnswersBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
                ->add('category', 'choice',
                        array('choices' => array('ciao', 'mondo'),
                                'multiple' => false))->add('title')
                ->add('description');
    }

    public function getName()
    {
        return 'universibo_bundle_answersbundle_questiontype';
    }
}
