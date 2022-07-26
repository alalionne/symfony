<?php

namespace GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SortieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code')
            ->add('quantite')
            ->add('dosage')
            ->add('prix')
            ->add('conditionnement')
            ->add('produit', EntityType::class, array(
                'class'        => 'GestionBundle:Produit',
                'choice_label' => 'dci',
                'multiple'     => false,
                'required' => false))
            ->add('destination', EntityType::class, array(
                'class'        => 'GestionBundle:Destination',
                'choice_label' => 'nom',
                'multiple'     => false,
                'required' => false))
             ->add('datePeremption', DateType::class, array('widget' => 'single_text'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GestionBundle\Entity\Sortie'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'gestionbundle_sortie';
    }


}
