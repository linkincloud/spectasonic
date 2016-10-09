<?php

namespace Spectasonic\Back\UserManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;

class CountryFilterType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('id', Filters\NumberFilterType::class)
                ->add('nameFr', Filters\TextFilterType::class)
                ->add('nameEn', Filters\TextFilterType::class)
                ->add('continent', Filters\EntityFilterType::class, array(
                    'class' => 'Spectasonic\Back\UserManagerBundle\Entity\Continent',
                    'choice_label' => 'nameFr',
                ))

        ;
    }

    public function getBlockPrefix() {
        return 'spectasonic_back_usermanagerbundle_countryfiltertype';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }

}
