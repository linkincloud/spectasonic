<?php

namespace Spectasonic\Back\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SliderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('medias', 'collection', array(
                'type' => new MediasType(),
                'allow_add' => true
                
            ))
          
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spectasonic\Back\BlogBundle\Entity\Slider'
        ));
    }

    public function getBlockPrefix()
    {
        return 'spectasonic_back_blogbundle_slider';
    }
}
