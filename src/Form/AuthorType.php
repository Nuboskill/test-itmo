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
                'label' => 'Фамилия', 'attr' => ['maxlength' => 190, 'autocomplete' => 'off']
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Имя', 'attr' => ['maxlength' => 190, 'autocomplete' => 'off']
            ])
            ->add('middleName', TextType::class, [
                'label' => 'Отчество', 'required' => false,
                'attr' => ['maxlength' => 190, 'autocomplete' => 'off']
            ])
            ->add('submit', SubmitType::class, ['label' => 'Отправить'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}
