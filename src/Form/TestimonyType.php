<?php

namespace App\Form;

use App\Entity\Testimony;
use App\Entity\TestimonyCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestimonyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Votre témoignage',
                'attr' => [
                    'placeholder' => 'Votre témoignage doit faire au moins 50 caractères.'
                ],
            ])
            ->add('testimonyCategory', EntityType::class, [
                'label' => 'Catégorie',
                'class' => TestimonyCategory::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez une catégorie',
                'help' => 'Choisissez une catégorie pour votre témoignage.'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer mon témoignage',
                'attr' => [
                    'class' => 'btn btn-style-two',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Testimony::class,
        ]);
    }
}
