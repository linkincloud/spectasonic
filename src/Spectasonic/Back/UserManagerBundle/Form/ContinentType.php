<?php

namespace Spectasonic\Back\UserManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContinentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameFr', 'text', array(
                'label' => 'back.continent.add.form.label_namefr',
                'required' => false
            ))
            ->add('nameEn', 'text', array(
                'label' => 'back.continent.add.form.label_nameen',
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
            'data_class' => 'Spectasonic\Back\UserManagerBundle\Entity\Continent'
        ));
    }
}
