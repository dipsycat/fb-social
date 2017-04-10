<?php

namespace Dipsycat\FbSocialBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\FileType;

class ImageTypeExtension extends \Symfony\Component\Form\AbstractTypeExtension {
    
    public function getExtendedType() {
        return FileType::class;
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver) {
        $resolver->setDefined(array('image_path'));
    }

    public function buildView(\Symfony\Component\Form\FormView $view, \Symfony\Component\Form\FormInterface $form, array $options) {
        if (isset($options['image_path'])) {
            $parentData = $form->getParent()->getData();
            
            $imageUrl = null;
            if (null !== $parentData) {
                $accessor = \Symfony\Component\PropertyAccess\PropertyAccess::createPropertyAccessor();
                $imageUrl = $accessor->getValue($parentData, $options['image_path']);
            }

            // set an "image_url" variable that will be available when rendering this field
            $view->vars['image_url'] = $imageUrl;
        }
    }
}
