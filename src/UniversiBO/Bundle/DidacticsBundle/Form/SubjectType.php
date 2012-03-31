<?php

namespace UniversiBO\Bundle\DidacticsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class SubjectType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('description')
        ;
    }

    public function getName()
    {
        return 'universibo_bundle_didacticsbundle_subjecttype';
    }
}
