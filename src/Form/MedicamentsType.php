<?php

namespace App\Form;

use App\Entity\Medicaments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MedicamentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('image', FileType::class, [
          'label' => 'Image du Médicament',
          'mapped' => false, // Ne pas lier directement à l'entité
          'required' => false,
          'attr' => ['class' => 'custom-file-upload', 'id' => 'medicament_image'],
          'constraints' => [
              new File([
                  'maxSize' => '2M',
                  'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                  'mimeTypesMessage' => 'Veuillez uploader une image valide (JPEG, PNG, WEBP).',
              ])
          ],
      ])
      

            ->add('nom')
            ->add('description')
            ->add('classe')
            ->add('prix');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medicaments::class,
        ]);
    }
}
