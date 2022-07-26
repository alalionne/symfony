<?php

namespace GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class EntreeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('datePeremption', DateType::class, array('widget' => 'single_text',
                'html5' => false,'attr' => ['class' => 'js-datepicker'],))
            ->add('dosage')
            ->add('prix')
            ->add('conditionnement')
            ->add('quantite')
            ->add('annee', DateType::class, array('widget' => 'single_text'))
            ->add('produit', EntityType::class, array(
                'class'        => 'GestionBundle:Produit',
                'choice_label' => 'dci',
                'multiple'     => false,
                'required' => false))
            ->add('utilisation', EntityType::class, array(
                'class'        => 'GestionBundle:Utilisation',
                'choice_label' => 'nom',
                'multiple'     => false,
                'required' => false)); 
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionBundle\Entity\Entree'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionbundle_entree';
    }


}
