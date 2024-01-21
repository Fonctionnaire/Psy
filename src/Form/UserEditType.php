<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserAvatar;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('userAvatar', UserAvatarType::class)
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'required' => false,
                'help' => 'Laissez vide pour ne pas modifier votre email.',
                'attr' => [
                    'placeholder' => 'Laissez vide pour ne pas modifier votre email.',
                ],
                'mapped' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\\S+@\\S+\\.\\S+$/',
                        'message' => 'Veuillez saisir une adresse email avec un format valide. Ex : email@email.fr',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'required' => false,
                'label' => 'Nouveau mot de passe',
                'attr' => [
                    'placeholder' => 'Laissez vide pour ne pas modifier le mot de passe.',
                ],
                'help' => 'Laissez vide pour ne pas modifier le mot de passe. Le mot de passe doit contenir au moins 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial (#?!@$%^&*-).',
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                        'max' => 255,
                        'maxMessage' => 'Le mot de passe ne peut excéder {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                        'message' => 'Le mot de passe doit contenir au moins 8 caractères, 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial (#?!@$%^&*-).',
                    ]),
                ],
            ])
            ->add('username', TextType::class, [
                'label' => 'Votre pseudo',
                'attr' => [
                    'maxlength' => 15,
                    'placeholder' => '15 caractères maximum - sans espace',
                ],
            ])
            ->add('firstname', TextType::class, [
                'required' => false,
                'label' => 'Votre prénom',
                'help' => 'Ce champ est facultatif.',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider les modifications',
                'attr' => [
                    'class' => 'btn btn-style-one',
                ],
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
