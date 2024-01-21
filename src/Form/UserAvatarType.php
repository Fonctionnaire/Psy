<?php

namespace App\Form;

use App\Entity\UserAvatar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UserAvatarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'label' => 'Votre avatar',
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer l\’avatar',
                'download_uri' => true,
                'download_label' => false,
                'image_uri' => true,
                'asset_helper' => true,
                'help' => 'Laissez vide pour ne pas modifier votre avatar.',
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'maxSizeMessage' => 'L\'image ne peut excéder {{ limit }}.',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserAvatar::class,
        ]);
    }
}
