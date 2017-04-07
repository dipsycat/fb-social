<?php

namespace Dipsycat\FbSocialBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UserType extends AbstractType {

    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options) {
        $builder
                ->add('username', TextType::class, [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Name',
                        'class' => 'form-control'
                    ]
                ])
                ->add('surname', TextType::class, [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'SurName',
                        'class' => 'form-control'
                    ]
                ])
                ->add('avatar', FileType::class, [
                    'label' => false,
                    'attr' => [
                        //'placeholder' => 'SurName',
                        'class' => 'form-control'
                    ]
                ]);
    }

    public function getName() {
        return 'user';
    }

    public function getDefaultOptions(array $options) {
        return array(
            //'data_class' => 'Dipsycat\FbSocialBundle\Entity\User',
            'validation_groups' => array('edit')
        );
    }

}
