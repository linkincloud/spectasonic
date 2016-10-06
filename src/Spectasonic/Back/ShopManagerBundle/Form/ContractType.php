<?php

namespace Spectasonic\Back\ShopManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reference')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spectasonic\Back\ShopManagerBundle\Entity\Contract'
        ));
    }

    public function getBlockPrefix()
    {
        return 'spectasonic_back_shopmanagerbundle_contract';
    }
}
