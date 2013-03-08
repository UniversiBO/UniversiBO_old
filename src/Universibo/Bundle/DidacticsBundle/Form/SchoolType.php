<?php

namespace Universibo\Bundle\DidacticsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SchoolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('url')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Universibo\Bundle\DidacticsBundle\Entity\School'
        ));
    }

    public function getName()
    {
        return 'universibo_bundle_didacticsbundle_schooltype';
    }
}
