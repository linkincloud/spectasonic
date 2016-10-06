<?php

namespace Spectasonic\Back\ShopManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class User_contractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            
            /*->add('contract',    'entity', array(
                'class'          => 'SpectasonicBackShopManagerBundle:Contract',
                'property'       => 'reference',
                'multiple'       => false,
                'expanded'       => false
            ))*/
            ->add('contract', new ContractType(), array(
                'label'        => 'Create a new Contract',
            ))
            ->add('user',       'entity', array(
                'label'          => 'Choose user',
                'class'          => 'SpectasonicFrontUserBundle:User',
                'property'       => 'username',
                'multiple'       => false,
                'expanded'       => false
            ))
            ->add('quantity', 'number')
            ->add('product',     'entity', array(
                'class'          => 'SpectasonicBackShopManagerBundle:ShopProduct',
                'property'       => 'reference',
                'multiple'       => false,
                'expanded'       => false
            )) 
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spectasonic\Back\ShopManagerBundle\Entity\User_contract'
        ));
    }

    public function getBlockPrefix()
    {
        return 'spectasonic_back_shopmanagerbundle_user_contract';
    }
}
