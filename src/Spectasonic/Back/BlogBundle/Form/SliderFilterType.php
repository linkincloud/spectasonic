<?php

namespace Spectasonic\Back\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;


class SliderFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('name', Filters\TextFilterType::class)
            ->add('description', Filters\TextFilterType::class)
            ->add('createdAt', Filters\DateTimeFilterType::class)
            ->add('updatedAt', Filters\DateTimeFilterType::class)
            ->add('slug', Filters\TextFilterType::class)
        ;


    }

    public function getBlockPrefix()
    {
        return 'spectasonic_back_blogbundle_sliderfiltertype';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }
}
