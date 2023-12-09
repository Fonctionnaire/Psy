<?php

namespace App\Form;

use App\Entity\ForumCategory;
use App\Entity\ForumSubject;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForumSubjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', TextType::class, [
                'label' => 'Titre du sujet',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu du sujet',
            ])
            ->add('forumCategory', EntityType::class, [
                'class' => ForumCategory::class,
                'choice_label' => 'name',
                'label' => 'Catégorie du sujet',
                'placeholder' => 'Choisir une catégorie',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer le sujet',
                'attr' => [
                    'class' => 'btn btn-style-forum',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ForumSubject::class,
            'csrf_protection' => false,
        ]);
    }
}
