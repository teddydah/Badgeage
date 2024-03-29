<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr' => [
                    'placeholder' => 'Adresse mail'
                ],
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                $form->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe ne correspondent pas.',
                    'mapped' => false,
                    'first_options' => [
                        'label' => 'Mot de passe',
                        'help' => 'Votre mot de passe doit contenir au moins 8 caractères.',
                        'attr' => [
                            'placeholder' => 'Nouveau mot de passe'
                        ],
                        'constraints' => [
                            new Length(null, '8'),
                            new Regex(
                                '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&-\/])[A-Za-z\d@$!%*#?&-\/]{8,}$/',
                                'Votre mot de passe peut contenir les caractères spéciaux suivants : @$!%*#?&-/'
                            ),
                            new NotCompromisedPassword(
                                [
                                    'message' => 'Ce mot de passe a été divulgué lors d\'une fuite de données. Veuillez choisir un autre mot de passe.'
                                ]
                            ),
                        ],
                    ],
                    'second_options' => [
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'Veuillez saisir à nouveau votre mot de passe'
                        ]
                    ]
                ]);
            });

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder
                ->add('roles', ChoiceType::class, [
                    'label' => 'Rôle',
                    'multiple' => true,
                    'choices' => [
                        'Utilisateur' => 'ROLE_USER',
                        'Administrateur' => 'ROLE_ADMIN'
                    ],
                    'constraints' => new Count('1')
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
