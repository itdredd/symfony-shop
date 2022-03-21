<?php

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('name', TextType::class)

            ->add('price', IntegerType::class)
            ->add('durations', IntegerType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 1
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => false
            ])
            ->add('forumUrl', TextType::class, [
                'required' => false,
                'attr'  => [
                    'maxlength' => 255
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class
            ])
            ->add('currency', CurrencyType::class)
            ->add('save', SubmitType::class, ['label' => 'Save product']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
