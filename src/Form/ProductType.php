<?php

namespace App\Form;

use App\Entity\Products;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('image', FileType::class, [
                'label' => 'Product Image',
                'required' => false,
                'mapped' => false,
            ])
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'TV' => 'TV',
                    'PC' => 'PC',
                    'CONSOLE' => 'CONSOLE',
                    'ELECTROMENAGER' => 'ELECTROMENAGER',
                ],
                'placeholder' => 'Choose a category',
            ])
            ->add('quantite')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
