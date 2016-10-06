<?php

namespace Spectasonic\Front\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//use FOS\UserBundle\Form\Type\RegistrationFormType;
use Spectasonic\Front\UserBundle\Form\Type\RegisterType;

/**
 * Extends la RegisterType de UserBundle
 */
class AddUserType extends RegisterType {
 
    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        $builder
                ->add('roles', 'collection', array(
                    'label' => 'profile.fields.roles',
                    'translation_domain' => 'forms',
                    'type' => 'choice',
                    'options' => array(
                        'choices' => array(
                            'ROLE_ADMIN' => 'ROLE_ADMIN',
                            'ROLE_EDITEUR' => 'ROLE_EDITEUR',
                            'ROLE_MODERATEUR' => 'ROLE_MODERATEUR',
                            'ROLE_EXPERT' => 'ROLE_EXPERT',
                            'ROLE_VENDEUR' => 'ROLE_VENDEUR',
                            'ROLE_USER' => 'ROLE_USER',
                        ),
                    ),
                ))
                ->add('phone', 'text', array(
                    'label' => 'profile.fields.phone',
                    'required' => false,
                    'translation_domain' => 'forms',
                    
        ));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Spectasonic\Front\UserBundle\Entity\User'
        ));
    }

    public function getName() {
        return 'spectasonic_front_add_user';
    }

}
