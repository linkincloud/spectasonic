<?php

namespace Spectasonic\Front\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\UserBundle\Form\Type\RegistrationFormType;

class RegisterType extends RegistrationFormType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        $builder
                ->add('gender', 'choice', array(
                    'label' => 'profile.fields.gender',
                    'translation_domain' => 'forms',
                    'choices' => array(
                        'M' => 'M.',
                        'Mme' => 'Mme',
                    )
                ))
                ->add('firstname', 'text', array(
                    'label' => 'profile.fields.firstname',
                    'translation_domain' => 'forms'
                ))
                ->add('lastname', 'text', array(
                    'label' => 'profile.fields.lastname',
                    'translation_domain' => 'forms'
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Spectasonic\Front\UserBundle\Entity\User'
        ));
    }

    public function getName() {
        return 'spectasonic_front_fos_user_register';
    }

}
