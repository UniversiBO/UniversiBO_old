<?php

namespace Universibo\Bundle\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SuggestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAttribute('legend', 'suggestion.form.legend')
            ->add('title', 'text', ['label' => 'suggestion.form.title'])
            ->add('description', 'textarea', ['label' => 'suggestion.form.description'])
            ->add('wouldHelp', 'checkbox', ['required' => false, 'label' => 'suggestion.form.would_help'])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Universibo\Bundle\MainBundle\Entity\Suggestion'
        ));
    }

    public function getName()
    {
        return 'universibo_bundle_mainbundle_suggestiontype';
    }
}
