<?php
namespace App\Form;

use App\Entity\Sponsor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType; // Utilisation de DateTimeType pour une meilleure gestion de la date
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert; // Ajout de l'importation pour les contraintes

class EventsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Entrez le titre de l\'événement'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Entrez la description de l\'événement'],
            ])
            ->add('lieu', TextType::class, [
                'label' => 'Lieu',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Entrez le lieu de l\'événement'],
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date et Heure',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
                'input' => 'datetime',
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image (facultatif)',
                'mapped' => false,  // Cela signifie que l'image ne sera pas directement associée à un champ de l'entité
                'required' => false, // L'image est facultative, donc le champ n'est pas obligatoire
                'attr' => ['class' => 'form-control-file'], // Classes Bootstrap pour un affichage plus joli
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG ou WebP)',
                    ])
                ]
            ])
            ->add('idSponsor', EntityType::class, [
                'class' => Sponsor::class,
                'choice_label' => 'name', // Vous pouvez changer cela pour afficher un champ plus descriptif
                'placeholder' => 'Sélectionnez un sponsor',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ]);
    }
}