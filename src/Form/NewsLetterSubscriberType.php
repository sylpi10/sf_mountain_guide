<?php

namespace App\Form;

use App\Entity\NewsLetterSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\IsTrue;

class NewsLetterSubscriberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullname', TextType::class, [
                "label" => false
            ])
            ->add('email', EmailType::class, [
                "label" => false
            ])
            ->add('isRgpd', CheckboxType::class, [
                "label" => false,
                "constraints" => new IsTrue([
                    'message' => "vous devez accepter"
                ])
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NewsLetterSubscriber::class,
        ]);
    }
}
