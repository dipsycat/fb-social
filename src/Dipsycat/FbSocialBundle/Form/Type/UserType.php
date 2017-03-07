<?php

namespace Dipsycat\FbSocialBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options) {
        $builder
                ->add('username', TextType::class, [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Name',
                    ]
                ])
                ->add('surname', TextType::class, [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'SurName',
                    ]
                ]);
    }
    
    public function getName() {
        return 'user';
    }
}