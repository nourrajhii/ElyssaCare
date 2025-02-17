<?php

namespace App\Form;

use App\Entity\Commentaire;
use App\Entity\Blog;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_utilisateur')
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('contenu')
            ->add('nombre_like')
            ->add('blog', EntityType::class, [  // Association du commentaire avec un blog
                'class' => Blog::class,
                'choice_label' => 'titre',  // Affichage des titres des blogs dans le champ de sélection
                'placeholder' => 'Sélectionnez un blog',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
