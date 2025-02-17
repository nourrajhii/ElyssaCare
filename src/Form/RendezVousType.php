<?php

namespace App\Form;

use App\Entity\RendezVous;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Laboratoire;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class RendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('patient_nom')
            ->add('patient_email')
            ->add('patient_telephone')
            ->add('date')
            ->add('heure')
            ->add('etat')
            
            ->add('laboratoire', EntityType::class, [
                'class' => Laboratoire::class,
                'choice_label' => 'nomLaboratoire',
                'placeholder' => 'Sélectionnez un laboratoire',
                'data' => isset($options['laboratoire']) ? $options['laboratoire'] : null, // Valeur par défaut null si non défini
                'disabled' => isset($options['laboratoire']) && $options['laboratoire'] !== null, // Désactiver si laboratoire est défini
            ])
            ->add('analyse')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RendezVous::class,
        ]);
    }
}
