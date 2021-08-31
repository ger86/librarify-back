<?php

namespace App\Form\Type;

use App\Form\Model\BookDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('base64Image', TextType::class)
            ->add('description', TextareaType::class)
            ->add('score', NumberType::class)
            ->add('readAt', DateType::class, ['widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'html5' => false])
            ->add('categories', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => CategoryFormType::class
            ])
            ->add('authors', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => AuthorFormType::class
            ])
            ->add('isbn', IsbnFormType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BookDto::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

    public function getName()
    {
        return '';
    }
}
