<?php

namespace Spectasonic\Back\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FM\ElfinderBundle\Form\Type\ElFinderType;

class BlogCategoryType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', 'text', array(
                    'required' => true,
                    'label' => 'back.blog.category.form.label_name'
                ))
                ->add('mainimage', new ElFinderType(), array(
                    'label' => 'back.blog.category.form.label_mainimage',
                    'required' => true,
                    'instance' => 'form_image',
                    'enable' => true
                ))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Spectasonic\Back\BlogBundle\Entity\BlogCategory'
        ));
    }

    public function getBlockPrefix() {
        return 'spectasonic_back_blogbundle_blogcategory';
    }

}
