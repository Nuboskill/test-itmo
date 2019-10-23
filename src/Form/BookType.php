<?php

namespace App\Form;

use App\Entity\{Book, Author};
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType, IntegerType, SubmitType, FileType};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'name.label',
                'attr' => ['maxlength' => 190, 'autocomplete' => 'off', 'placeholder' => 'name.placeholder'],
            ])
            ->add('cover', FileType::class, [
                'label' => 'cover.label',
                'required' => false,
                'attr' => ['accept' => 'image/*', 'placeholder' => 'cover.placeholder'],
            ])
            ->add('year', IntegerType::class, [
                'label' => 'year.label',
                'attr' => ['max' => date('Y'), 'placeholder' => 'year.placeholder'],
            ])
            ->add('isbn', TextType::class, [
                'label' => 'isbn.label',
                'attr' => ['maxlength' => 190, 'autocomplete' => 'off', 'placeholder' => 'isbn.placeholder'],
            ])
            ->add('pagesCount', IntegerType::class, [
                'label' => 'pages_count.label',
                'required' => false,
                'attr' => ['placeholder' => 'pages_count.placeholder'],
            ])
            ->add('authors', EntityType::class, [
                'label' => 'authors.label',
                'class' => Author::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
            ])
            ->add('submit', SubmitType::class, ['label' => 'submit'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'books',
            'data_class' => Book::class,
        ]);
    }
}
