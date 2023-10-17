<?php

namespace App\Form;

use App\Entity\UserReview;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rate', ChoiceType::class, [
                'label' => 'Votre note',
                'label_attr' => [
                    'class' => 'form-rate-label',
                ],
                'attr' => [],
                'choices' => [
                    '1' => 5,
                    '2' => 4,
                    '3' => 3,
                    '4' => 2,
                    '5' => 1,
                ],
                'expanded' => true,
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Votre avis',
                'label_attr' => [
                    'class' => 'text-white mb-2 fw-bold',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Écrivez votre avis ici... 10 caractères minimum.',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'btn btn-style-two mt-4',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserReview::class,
        ]);
    }
}
