<?php

namespace Spectasonic\Back\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HomepageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('enabled', 'checkbox', array(
                    'label' => 'back.configuration.homepage.edit.form.label_enabled',
                    'required' => false
                ))
                ->add('slider', 'entity', array(
                    'label' => 'back.configuration.homepage.edit.form.label_slider',
                    'class' => 'SpectasonicBackCoreBundle:Slider',
                    'property' => 'name'
                    
                    
                ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spectasonic\Back\CoreBundle\Entity\Homepage'
        ));
    }
}
