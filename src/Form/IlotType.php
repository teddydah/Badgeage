<?php

namespace App\Form;

use App\Entity\Ilot;
use App\Entity\Printer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IlotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomIRL', null, [
                'label' => 'Nom IRL',
                'attr' => [
                    'placeholder' => 'Ex : Adhésif'
                ]
            ])
            ->add('nomURL', null, [
                'label' => 'Nom URL',
                'attr' => [
                    'placeholder' => 'Ex : Adhesif'
                ]
            ])
            ->add('nomAX', null, [
                'label' => 'Nom AX',
                'attr' => [
                    'min' => 1,
                    'placeholder' => 'Ex : 100'
                ]
            ])
            ->add('initiales', null, [
                'attr' => [
                    'placeholder' => 'Ex : ADH'
                ]
            ])
            ->add('printer', EntityType::class, [
                'label' => 'Imprimante',
                'class' => Printer::class,
                'placeholder' => '-- Choisir une imprimante --',
                'choice_label' => 'nom'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ilot::class,
        ]);
    }
}
