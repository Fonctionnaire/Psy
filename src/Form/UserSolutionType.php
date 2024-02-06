<?php

namespace App\Form;

use App\Entity\UserSolution;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSolutionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', ChoiceType::class, [
                'placeholder' => 'Choisir une réponse',
                'label' => 'De quoi pensez-vous souffrir ?',
                'placeholder_attr' => [
                    'class' => 'soluceFormPlaceholder',
                ],
                'choices' => [
                    'Je ne sais pas' => 'idk',
                    'Trouble anxieux' => 'anxiety',
                    'Dépression' => 'depression',
                    'Trouble bipolaire' => 'bipolar',
                    'Trouble de la personnalité' => 'personality',
                    'Burnout' => 'burnout',
                    'Agoraphobie' => 'agoraphobia',
                    'Phobies' => 'phobia',
                    'Trouble obsessionnel compulsif' => 'toc',
                    'Solitude' => 'loneliness',
                ],
                'choice_attr' => [
                    'class' => 'form-select',
                ],
                'label_attr' => [
                    'class' => 'soluceFormLabel',
                ],
            ])
            ->add('currentState', ChoiceType::class, [
                'placeholder' => 'Choisir une fréquence',
                'label' => 'À quelle fréquence vous sentez vous mal ?',
                'placeholder_attr' => [
                    'class' => 'soluceFormPlaceholder',
                ],
                'choices' => [
                    'De temps en temps' => 'low',
                    'Plusieurs fois par semaine' => 'medium',
                    'Tous les jours' => 'high',
                ],
                'choice_attr' => [
                    'class' => 'form-select',
                ],
                'label_attr' => [
                    'class' => 'soluceFormLabel',
                ],
            ])
            ->add('isDisabling', ChoiceType::class, [
                'label' => 'Est-ce que votre état vous empêche de faire des choses du quotidien ? Que vous faisiez avant sans difficultés.',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'choice_attr' => [
                    'class' => 'form-select',
                ],
                'label_attr' => [
                    'class' => 'soluceFormLabel',
                ],
                'expanded' => true,
            ])
            ->add('howLong', ChoiceType::class, [
                'placeholder' => 'Choisir une réponse',
                'label' => 'Depuis combien de temps êtes vous dans cet état ?',
                'choices' => [
                    'Quelques jours' => 'days',
                    'Plusieurs mois' => 'months',
                    'Un an ou plus' => 'years',
                ],
                'choice_attr' => [
                    'class' => 'form-select',
                ],
                'label_attr' => [
                    'class' => 'soluceFormLabel',
                ],
            ])
            ->add('isSource', ChoiceType::class, [
                'required' => true,
                'label' => 'Avez-vous identifié une source à votre mal-être ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'placeholder' => 'Choisir une réponse',
                'expanded' => true,
            ])
            ->add('isExcitingProduct', ChoiceType::class, [
                'label' => 'Consommez-vous quotidiennement un ou plusieurs produits excitants ?',
                'help' => 'Café, thé, soda, tabac, alcool, drogues, médicaments, etc.',
                'placeholder_attr' => [
                    'class' => 'soluceFormPlaceholder',
                ],
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'choice_attr' => [
                    'class' => 'form-select',
                ],
                'label_attr' => [
                    'class' => 'soluceFormLabel',
                ],
                'help_attr' => [
                    'class' => 'soluceFormHelp',
                ],
                'expanded' => true,
            ])
            ->add('isDoctorConsulted', ChoiceType::class, [
                'required' => true,
                'label' => 'Avez-vous consulté votre médecin traitant ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'choice_attr' => [
                    'class' => 'form-select',
                ],
                'label_attr' => [
                    'class' => 'soluceFormLabel',
                ],
                'expanded' => true,
            ])
            ->add('isPsyConsulted', ChoiceType::class, [
                'required' => true,
                'label' => 'Avez-vous déjà consulté un psychologue ou un psychiatre ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'attr' => [
                    'class' => 'solution-form-psy-consulted',
                ],
                'choice_attr' => [
                    'class' => 'form-select',
                ],
                'label_attr' => [
                    'class' => 'soluceFormLabel',
                ],
                'expanded' => true,
            ])
            ->add('isPsyAfraid', ChoiceType::class, [
                'required' => false,
                'label' => 'Avez-vous des craintes par rapport à ces professionnels de santé ?',
                'placeholder' => false,
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
                'expanded' => true,
                'empty_data' => 'false',
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
            'data_class' => UserSolution::class,
        ]);
    }
}
