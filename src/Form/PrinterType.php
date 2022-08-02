<?php

namespace App\Form;

use App\Entity\Printer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrinterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'attr' => [
                    'placeholder' => 'Ex : Chef Atelier'
                ]
            ])
            ->add('ip', null, [
                'attr' => [
                    'placeholder' => 'Ex : 192.168.35.207'
                ]
            ])
            ->add('port')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Printer::class,
        ]);
    }
}
