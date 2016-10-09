<?php

namespace Spectasonic\Back\UserManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CountryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameFr', 'text', array(
                'label' => 'back.country.new.form.label_namefr',
                'required' => true
            ))
            ->add('nameEn', 'text', array(
                'label' => 'back.country.new.form.label_nameen',
                'required' => false
            ))
            ->add('continent', EntityType::class, array(
                'class' => 'Spectasonic\Back\UserManagerBundle\Entity\Continent',
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spectasonic\Back\UserManagerBundle\Entity\Country'
        ));
    }
}
