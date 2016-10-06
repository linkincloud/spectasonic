<?php

namespace Spectasonic\Back\ShopManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FM\ElfinderBundle\Form\Type\ElFinderType;

class ShopCategoryType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', 'text', array(
                    'required' => true,
                    'label' => 'back.shop.category.edit.form.label_name'
                ))
                ->add('mainimage', new ElFinderType(), array(
                    'label' => 'back.shop.category.edit.form.label_mainimage',
                    'required' => true,
                    'instance' => 'form_image',
                    'enable' => true
                ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Spectasonic\Back\ShopManagerBundle\Entity\ShopCategory'
        ));
    }

}
