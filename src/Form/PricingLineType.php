<?php

namespace App\Form;

use App\Entity\PricingLine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Ligne de tarif, éditée dans le formulaire d'un bloc (admin).
 */
class PricingLineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Libellé (FR)',
                'required' => false,
                'attr' => ['placeholder' => 'Ex. : Voie normale'],
            ])
            ->add('price', TextType::class, [
                'label' => 'Prix (FR)',
                'required' => false,
                'attr' => ['placeholder' => 'Ex. : 1000€, à partir de 1350€, 400€/j'],
            ])
            ->add('labelEn', TextType::class, [
                'label' => 'Libellé (EN)',
                'required' => false,
                'attr' => ['placeholder' => 'Si vide : libellé FR'],
            ])
            ->add('priceEn', TextType::class, [
                'label' => 'Prix (EN)',
                'required' => false,
                'attr' => ['placeholder' => 'Si vide : prix FR'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PricingLine::class,
            'row_attr' => ['class' => 'pricing-line-form'],
        ]);
    }
}
