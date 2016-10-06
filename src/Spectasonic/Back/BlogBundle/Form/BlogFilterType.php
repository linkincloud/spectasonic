<?php

namespace Spectasonic\Back\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;


class BlogFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', Filters\NumberFilterType::class)
            ->add('date', Filters\DateTimeFilterType::class)
            ->add('title', Filters\TextFilterType::class)
            ->add('author', Filters\TextFilterType::class)
            ->add('excerpt', Filters\TextFilterType::class)
            ->add('published', Filters\BooleanFilterType::class)
            ->add('page', Filters\BooleanFilterType::class)
            ->add('updatedAt', Filters\DateTimeFilterType::class)
            ->add('nbComments', Filters\NumberFilterType::class)
        ;


    }

    public function getBlockPrefix()
    {
        return 'spectasonic_back_blogbundle_blogfiltertype';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }
}
