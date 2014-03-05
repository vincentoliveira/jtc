<?php

namespace Jtc\AnnonceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AnnonceContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom');
        $builder->add('email', 'email');
        $builder->add('sujet');
        $builder->add('contenu', 'textarea');
    }
 
    public function getName()
    {
        return 'annonce_contact';
    }
}
