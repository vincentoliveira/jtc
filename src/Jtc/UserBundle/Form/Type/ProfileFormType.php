<?php

namespace Jtc\UserBundle\Form\Type;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends BaseType
{
    public function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildUserForm($builder, $options);
        // add your custom field
        $builder->add('telephone');
                 
    }
 
     
    public function getName()
    {
        return 'jtc_user_profile';
    }
     
}