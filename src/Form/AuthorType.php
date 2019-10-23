<?php

namespace App\Form;

use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{TextType, SubmitType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'last_name.label',
                'attr' => ['maxlength' => 190, 'autocomplete' => 'off', 'placeholder' => 'last_name.placeholder'],
            ])
            ->add('firstName', TextType::class, [
                'label' => 'first_name.label',
                'attr' => ['maxlength' => 190, 'autocomplete' => 'off', 'placeholder' => 'first_name.placeholder'],
            ])
            ->add('middleName', TextType::class, [
                'label' => 'middle_name.label',
                'required' => false,
                'attr' => ['maxlength' => 190, 'autocomplete' => 'off', 'placeholder' => 'middle_name.placeholder'],
            ])
            ->add('submit', SubmitType::class, ['label' => 'submit'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'authors',
            'data_class' => Author::class,
        ]);
    }
}
