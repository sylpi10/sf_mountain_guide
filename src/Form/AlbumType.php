<?php

namespace App\Form;

use App\Entity\Album;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AlbumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('englishTitle',TextType::class, [
                'label' => 'Titre Englais'
                ])
            ->add('date', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('location', TextType::class, [
                'label' => 'Localisation'
                ])
            ->add('content', TextareaType::class, [
                'label' => 'Description'
                ])
            ->add('englishContent', TextareaType::class, [
                'label' => 'Description En'
                ])
            // not linked to db -> mapped = false
            ->add('images', FileType::class, [
                'label' => "Importer des Photos",
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Album::class,
        ]);
    }
}
