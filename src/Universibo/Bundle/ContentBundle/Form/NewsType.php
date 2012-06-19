<?php

namespace Universibo\Bundle\ContentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('expiresAt', 'datetime', array('required' => false))
            ->add('content')
            ->add('urgent', 'checkbox', array('required' => false))
            ->add('channels')
        ;
    }

    public function getName()
    {
        return 'universibo_bundle_contentbundle_newstype';
    }
}
