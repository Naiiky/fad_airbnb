<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class BookingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('checkIn', DateType::class, [
                'label' => 'Arrivee',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(message: 'Veuillez choisir une date d arrivee.'),
                ],
            ])
            ->add('checkOut', DateType::class, [
                'label' => 'Depart',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(message: 'Veuillez choisir une date de depart.'),
                ],
            ])
            ->add('adultCount', IntegerType::class, [
                'label' => 'Adultes',
                'data' => 1,
                'constraints' => [
                    new GreaterThanOrEqual(value: 1, message: 'Indiquez au moins un adulte.'),
                ],
            ])
            ->add('childrenCount', IntegerType::class, [
                'label' => 'Enfants',
                'data' => 0,
                'constraints' => [
                    new GreaterThanOrEqual(value: 0, message: 'Le nombre d enfants ne peut pas etre negatif.'),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
