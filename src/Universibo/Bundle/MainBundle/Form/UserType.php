<?php
namespace Universibo\Bundle\MainBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Universibo\Bundle\CoreBundle\Form\UserType as BaseUserType;

class UserType extends BaseUserType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('username')
            ->remove('email')
        ;
    }
}
