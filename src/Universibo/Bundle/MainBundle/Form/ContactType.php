<?php

namespace Universibo\Bundle\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value', null, array('label' => 'Email'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Universibo\Bundle\MainBundle\Entity\Contact'
        ));
    }

    public function getName()
    {
        return 'universibo_bundle_mainbundle_contacttype';
    }
}
