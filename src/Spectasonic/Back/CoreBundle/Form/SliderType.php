<?php

namespace Spectasonic\Back\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FM\ElfinderBundle\Form\Type\ElFinderType;

class SliderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name')
                ->add('description', 'text', array(
                    'label' => 'Description',
                    'required' => false
                ))
                ->add('mainimage', new ElfinderType(), array(
                    'label' => 'back.configuration.slider.edit.form.label_image',
                    'instance' => 'form_image',
                    'enable' => true,
                    'required' => false
                ))
                ->add('video', new ElFinderType(), array(
                    'label' => 'back.configuration.slider.edit.form.label_video',
                    'instance' => 'form_video',
                    'enable' => true,
                    'required' => false
                ))
                ->add('slider')
                ->add('slider', 'entity', array(
                    'label' => 'back.configuration.slider.edit.form.label_slider',
                    'class' => 'SpectasonicBackBlogBundle:Slider',
                    'property' => 'name',
                    'multiple' => false,
                    'required' => false
                    
                ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spectasonic\Back\CoreBundle\Entity\Slider'
        ));
    }
}
