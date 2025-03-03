<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints as Assert;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;



class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles',  ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Doctor' => 'ROLE_DOCTOR',
                    'patient' => 'ROLE_PATIENT',
                    'Pharmacy' => 'ROLE_PHARMACY',

                    'constraints' => [
                    new Assert\NotBlank(message: "Le rôle est obligatoire.")
                    ]
                ],
            ])
            #->add('password')
            ->add('name');
            if (!$options['data']->getId()) { // Si l'utilisateur n'a pas d'ID, c'est un formulaire de création
                $builder->add('password', PasswordType::class, [
                    'required' => true,
                    'attr' => ['placeholder' => 'Enter password'],
                ]);
            } else { // Si c'est un formulaire d'édition, on ne met pas de valeur par défaut
                $builder->add('password', PasswordType::class, [
                    'required' => false,  // Le mot de passe est optionnel en mode édition
                    'mapped' => false,  // On ne lie pas le champ au mot de passe dans la base de données
                    'attr' => ['placeholder' => 'Leave blank to keep current password'],
                ]);
            }
            $builder ->add('submit', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-primary']
            ]);
        ;
        $builder ->add('captcha', Recaptcha3Type::class, [
            'constraints' => [new Recaptcha3()],
            'mapped' => false,
            'action_name' => 'user',
            'locale' => 'en',
        ]);

        $builder->get('roles')
        ->addModelTransformer(new CallbackTransformer(
            function ($rolesArray) {
                return count($rolesArray) ? $rolesArray[0] : null;
            },
            function ($rolesString) {
                return [$rolesString];
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
