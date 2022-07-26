<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use GestionBundle\Form\ImageType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('image',ImageType::class, array('required' => false))
        ->add('nom')
        ->add('prenom')
        ->add('username')
        ->add('tel')
        ->add('email')
        ->add('plainpassword', TextType::class, array('required' => false))
        ->add('roles', ChoiceType::class, array(
                        'choices' => array('Utilisateur'=>'ROLE_USER', 'Administrateur'=>'ROLE_ADMIN'),
                        'multiple' => true,
                        'expanded'=> true
                    ))
        ->add('enabled', CheckboxType::class, array('required' => false));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'userbundle_user';
    }


}
