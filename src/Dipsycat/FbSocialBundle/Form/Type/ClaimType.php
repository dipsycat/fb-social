<?php

namespace Dipsycat\FbSocialBundle\Form\Type;

use Dipsycat\FbSocialBundle\Entity\Claim;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ClaimType extends AbstractType {

    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options) {
        $builder
            /*->add('comment', TextType::class, [
                'label' => 'Comment',
                'attr' => [
                    'placeholder' => 'Comment',
                    'class' => 'form-control'
                ],
                'disabled' => true
            ])
            ->add('created_at', DateTimeType::class, [
                'label' => 'Created At',
                'attr' => [
                    'placeholder' => 'Created At',
                ],
                'disabled' => true
            ])*/
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' => Claim::getAllStatuses(),
                'attr' => [
                    'placeholder' => 'Status',
                    'class' => 'form-control'
                ]
            ]);
    }

    public function getName() {
        return 'claim';
    }

    public function getDefaultOptions(array $options) {
        return array();
    }

}
