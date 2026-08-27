<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Equipment;
use App\Entity\Property;
use App\Entity\PropertyCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class PropertyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'constraints' => [
                    new NotBlank(message: 'Veuillez saisir un titre.'),
                    new Length(max: 255, maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères.'),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'constraints' => [
                    new NotBlank(message: 'Veuillez décrire le logement.'),
                    new Length(min: 20, minMessage: 'La description doit contenir au moins {{ limit }} caractères.'),
                ],
            ])
            ->add('maxGuest', IntegerType::class, [
                'label' => 'Voyageurs',
                'constraints' => [
                    new Positive(message: 'La capacité doit être supérieure à zéro.'),
                ],
            ])
            ->add('bedrooms', IntegerType::class, [
                'label' => 'Chambres',
                'constraints' => [
                    new GreaterThanOrEqual(value: 0, message: 'Le nombre de chambres ne peut pas être négatif.'),
                ],
            ])
            ->add('bathrooms', IntegerType::class, [
                'label' => 'Salles de bain',
                'constraints' => [
                    new Positive(message: 'Indiquez au moins une salle de bain.'),
                ],
            ])
            ->add('beds', IntegerType::class, [
                'label' => 'Lits',
                'constraints' => [
                    new Positive(message: 'Indiquez au moins un lit.'),
                ],
            ])
            ->add('areaM2', IntegerType::class, [
                'label' => 'Surface',
                'constraints' => [
                    new Positive(message: 'La surface doit être supérieure à zéro.'),
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'constraints' => [
                    new NotBlank(message: 'Veuillez saisir une adresse.'),
                    new Length(max: 255, maxMessage: "L'adresse ne peut pas dépasser {{ limit }} caractères."),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'constraints' => [
                    new NotBlank(message: 'Veuillez saisir une ville.'),
                    new Length(max: 255, maxMessage: 'La ville ne peut pas dépasser {{ limit }} caractères.'),
                ],
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'Code postal',
                'constraints' => [
                    new NotBlank(message: 'Veuillez saisir un code postal.'),
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
            ->add('category', EntityType::class, [
                'class' => PropertyCategory::class,
                'choice_label' => 'label',
                'label' => 'Catégorie',
                'constraints' => [
                    new NotBlank(message: 'Veuillez sélectionner une catégorie.'),
                ],
            ])
            ->add('equipments', EntityType::class, [
                'class' => Equipment::class,
                'choice_label' => 'label',
                'expanded' => true,
                'label' => 'Équipements',
                'mapped' => false,
                'multiple' => true,
                'required' => false,
            ])
            ->add('images', FileType::class, [
                'label' => 'Photos du logement',
                'mapped' => false,
                'multiple' => true,
                'required' => false,
                'constraints' => [
                    new All([
                        new Image(
                            maxSize: '5M',
                            mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
                            maxSizeMessage: 'Chaque photo ne doit pas depasser {{ limit }} {{ suffix }}.',
                            mimeTypesMessage: 'Veuillez selectionner des images JPEG, PNG ou WebP.',
                            detectCorrupted: true,
                            corruptedMessage: "L'une des images semble corrompue.",
                        ),
                    ]),
                ],
            ])
            ->add('petsAllowed', CheckboxType::class, [
                'label' => 'Animaux acceptés',
                'required' => false,
            ])
            ->add('nightlyPrice', IntegerType::class, [
                'label' => 'Prix par nuit',
                'constraints' => [
                    new Positive(message: 'Le prix par nuit doit être supérieur à zéro.'),
                ],
            ])
            ->add('weekendPrice', IntegerType::class, [
                'label' => 'Prix week-end',
                'constraints' => [
                    new Positive(message: 'Le prix week-end doit être supérieur à zéro.'),
                ],
            ])
            ->add('cleaningFee', IntegerType::class, [
                'label' => 'Frais de ménage',
                'constraints' => [
                    new GreaterThanOrEqual(value: 0, message: 'Les frais de ménage ne peuvent pas être négatifs.'),
                ],
            ])
            ->add('deposit', IntegerType::class, [
                'label' => 'Caution',
                'constraints' => [
                    new GreaterThanOrEqual(value: 0, message: 'La caution ne peut pas être négative.'),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
