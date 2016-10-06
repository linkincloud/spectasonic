<?php

namespace Spectasonic\Back\ShopManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FM\ElfinderBundle\Form\Type\ElFinderType;

class ShopProductType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', 'text')
                ->add('excerpt', 'text')
                ->add('content', 'ckeditor', array(
                    'label' => 'back.shop.edit.form.label_content'
                ))
                ->add('actived', 'checkbox')
                ->add('reference', 'text')
                ->add('category', 'entity', array(
                    'class' => 'SpectasonicBackShopManagerBundle:ShopCategory',
                    'property' => 'name',
                    'multiple' => false,
                    'expanded' => false
                ))
                ->add('mainimage', new ElfinderType(), array(
                    'label' => 'back.shop.edit.form.label_mainimage',
                    'instance' => 'form_image',
                    'enable' => true,
                    'required' => false
                ))
                ->add('document', new ElFinderType(), array(
                    'label' => 'back.shop.edit.form.label_document',
                    'required' => false,
                    'instance' => 'form_document',
                    'enable' => true
                ))
                ->add('slider', 'entity', array(
                    'required' => false,
                    'label' => 'back.shop.edit.form.label_slider',
                    'class' => 'SpectasonicBackBlogBundle:Slider',
                    'property' => 'name'
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Spectasonic\Back\ShopManagerBundle\Entity\ShopProduct'
        ));
    }

    public function getBlockPrefix() {
        return 'spectasonic_back_shopmanagerbundle_shopproduct';
    }

}
