<?php

namespace App\Form;

use App\Entity\Vehicle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Vehicle name',
            ])
            ->add('company', TextType::class, [
                'label' => 'Company',
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Sedan' => 'Sedan',
                    'Coupe' => 'Coupe',
                    'Sports Car' => 'Sports Car',
                    'Station Wagon' => 'Station Wagon',
                    'Hatchback' => 'Hatchback',
                    'Convertible' => 'Convertible',
                    'SUV' => 'SUV',
                    'MiniVan' => 'MiniVan',
                    'Truck' => 'Truck',
                    'Van' => 'Van',
                    'Motorcycle' => 'Motorcycle',
                ],
                'label' => 'Type of vehicle',
            ])
            ->add('model', TextType::class, [
                'label' => 'Model',
            ])
            ->add('year', IntegerType::class, [
                'label' => 'Year of manufacture',
            ])
            ->add('registerYear', IntegerType::class, [
                'label' => 'Registration year',
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Price',
                'currency' => 'USD',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
            ])
            ->add('color', TextType::class, [
                'label' => 'Color',
                'required' => false,
            ])
            ->add('fuel', ChoiceType::class, [
                'choices' => [
                    'Gasoline' => 'Gasoline',
                    'Diesel' => 'Diesel',
                    'Hybrid' => 'Hybrid',
                    'Electric' => 'Electric',
                ],
                'label' => 'Fuel type',
            ])
            ->add('plate', TextType::class, [
                'label' => 'License plate',
                'required' => false,
            ])
            ->add('km', IntegerType::class, [
                'label' => 'Kilometers',
                'required' => false,
            ])
            ->add('images', FileType::class, [
                'multiple' => true,
                'required' => false,
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
