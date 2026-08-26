<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Language;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank(message: 'Veuillez saisir votre prénom.'),
                    new Length(max: 255, maxMessage: 'Le prénom ne peut pas dépasser {{ limit }} caractères.'),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(message: 'Veuillez saisir votre nom.'),
                    new Length(max: 255, maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.'),
                ],
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'required' => false,
                'constraints' => [
                    new Length(max: 40, maxMessage: 'Le téléphone ne peut pas dépasser {{ limit }} caractères.'),
                    new Regex(pattern: '/^[0-9 +().-]*$/', message: 'Le téléphone contient des caractères non valides.'),
                ],
            ])
            ->add('avatar', UrlType::class, [
                'label' => 'Avatar',
                'required' => false,
                'default_protocol' => null,
                'constraints' => [
                    new Length(max: 255, maxMessage: "L'avatar ne peut pas dépasser {{ limit }} caractères."),
                    new Url(protocols: ['http', 'https'], message: 'Veuillez saisir une URL valide.'),
                ],
            ])
            ->add('bio', TextareaType::class, [
                'label' => 'Bio',
                'required' => false,
                'constraints' => [
                    new Length(max: 1000, maxMessage: 'La bio ne peut pas dépasser {{ limit }} caractères.'),
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'required' => false,
                'constraints' => [
                    new Length(max: 255, maxMessage: "L'adresse ne peut pas dépasser {{ limit }} caractères."),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'required' => false,
                'constraints' => [
                    new Length(max: 255, maxMessage: 'La ville ne peut pas dépasser {{ limit }} caractères.'),
                ],
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'Code postal',
                'required' => false,
                'constraints' => [
                    new Length(max: 20, maxMessage: 'Le code postal ne peut pas dépasser {{ limit }} caractères.'),
                ],
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'label',
                'label' => 'Pays',
                'constraints' => [
                    new NotBlank(message: 'Veuillez sélectionner un pays.'),
                ],
            ])
            ->add('languages', EntityType::class, [
                'class' => Language::class,
                'choice_label' => 'label',
                'expanded' => true,
                'label' => 'Langues',
                'mapped' => false,
                'multiple' => true,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
