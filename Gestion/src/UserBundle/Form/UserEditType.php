<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->remove('roles', ChoiceType::class, array(
                        'choices' => array('Utilisateur'=>'ROLE_USER', 'Administrateur'=>'ROLE_ADMIN'),
                        'multiple' => true,
                        'expanded'=> true
                    ))
        ->remove('enabled', CheckboxType::class, array('required' => false));
    }
    
    public function getParent()
    {
        return UserType::class;
    }


}
