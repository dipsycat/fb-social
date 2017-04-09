<?php

namespace Dipsycat\FbSocialBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationType extends AbstractType {

    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options) {
        $builder
            ->add('username', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Name',
                    'class' => 'form-control'
                ],
                'invalid_message' => 'This username already is used',
            ])
            ->add('surname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Surname',
                    'class' => 'form-control'
                ]
            ])
            /*->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email',
                    'class' => 'form-control'
                ]
            ])*/
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Password',
                        'class' => 'form-control'
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Repeat Password',
                        'class' => 'form-control'
                    ]
                ],
                'invalid_message' => 'Passwords are not same',
            ]);
    }

    public function getName() {
        return 'registration';
    }

    public function getDefaultOptions(array $options) {
        return array(//'data_class' => 'Dipsycat\FbSocialBundle\Entity\User',
        );
    }

}
