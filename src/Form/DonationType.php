<?php

namespace App\Form;

use App\Entity\Donation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DonationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse e-mail'
            ])
            ->add('price', NumberType::class, [
                'label' => 'Montant (en Euro)',
                'attr' => ['placeholder' => '2€ minimum']
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Vous pouvez me laisser un commentaire',
                'required' => false,
                'attr' => ['placeholder' => 'Champ facultatif']
            ])
            ->add('isAcceptTerms', CheckboxType::class, [
                'value' => 0,
                'attr' => ['class' => 'form-input-terms']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider votre don',
                'attr' => ['class' => 'btn']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Donation::class,
        ]);
    }
}
