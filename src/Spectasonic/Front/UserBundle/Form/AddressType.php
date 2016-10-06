<?php

namespace Spectasonic\Front\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('society', 'text', array(
                'required' => false
            ))
            ->add('person',  'text', array(
                'required' => false
            ))
            ->add('phone',   'text', array(
                'required' => false
            ))
            ->add('line1',   'text', array(
                'required' => true
            ))
            ->add('line2',   'text', array(
                'required' => false
            ))
            ->add('code',    'text', array(
                'required' => true
            ))
            ->add('city',    'text', array(
                'required' => true
            ))
            ->add('country', 'text', array(
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
            'data_class' => 'Spectasonic\Front\UserBundle\Entity\Address'
        ));
    }
}
