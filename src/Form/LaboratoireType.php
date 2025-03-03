<?php

namespace App\Form;

use App\Entity\Laboratoire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LaboratoireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_laboratoire')
            ->add('adresse')
            ->add('telephone')
            ->add('email')
            ->add('responsable')
            ->add('specialite')
            ->add('horaire_ouverture')
            ->add('horaire_fermeture')
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Laboratoire::class,
        ]);
    }
}
