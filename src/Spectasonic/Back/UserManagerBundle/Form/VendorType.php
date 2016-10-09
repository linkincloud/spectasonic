<?php

namespace Spectasonic\Back\UserManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class VendorType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('mainimage', new ElFinderType(), array(
                    'label' => 'back.vendor.informations.edit.form.label_mainimage',
                    'instance' => 'form_image',
                    'enable' => true,
                    'required' => false
                ))
                ->add('description', 'ckeditor', array(
                    'label' => 'back.vendor.informations.edit.form.label_content'
                ))
                ->add('urlToVendor', 'url', array(
                    'label' => 'back.vendor.informations.edit.form.label_url_to_vendor',
                    'required' => false
                ))
                ->add('urlToProduct', 'url', array(
                    'label' => 'back.vendor.informations.edit.form.label_url_to_product',
                    'required' => false
                ))
                ->add('country', EntityType::class, array(
                    'label' => 'back.vendor.informations.edit.form.label_country',
                    'class' => 'Spectasonic\Back\UserManagerBundle\Entity\Country',
                    'choice_label' => 'nameFr',
                    'placeholder' => 'Please choose',
                    'empty_data' => null,
                    'required' => true
                ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Spectasonic\Back\UserManagerBundle\Entity\Vendor'
        ));
    }

}
