<?php

namespace Spectasonic\Front\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\UserBundle\Form\Type\ProfileFormType;

class ProfileUserType extends ProfileFormType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('gender', 'choice', array(
                    'label' => 'profile.fields.gender',
                    'translation_domain' => 'forms',
                    'choices' => array(
                        'M' => 'M.',
                        'Mme' => 'Mme',
                    ),
                    'required' => false
                ))
                ->add('username', 'text', array(
                    'label' => 'profile.fields.username',
                    'translation_domain' => 'forms',
                    'required' => true
                ))
                ->add('firstname', 'text', array(
                    'label' => 'profile.fields.firstname',
                    'translation_domain' => 'forms',
                    'required' => false
                ))
                ->add('lastname', 'text', array(
                    'label' => 'profile.fields.lastname',
                    'translation_domain' => 'forms',
                    'required' => false
                ))
                ->add('email', 'email', array(
                    'label' => 'profile.fields.email',
                    'translation_domain' => 'forms',
                    'required' => true
                ))
                ->add('plainPassword', 'repeated', array(
                    'first_options' => array(
                        'label' => 'profile.fields.password_first',
                        'required' => false
                    ),
                    'second_options' => array(
                        'label' => 'profile.fields.password_second'
                    ),
                    'required' => false,
                    'translation_domain' => 'forms',
                    'required' => false
                ))
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
                ))
                
                
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'validation_groups' => array('Default', 'Account'),
            'data_class' => 'Spectasonic\Front\UserBundle\Entity\User'
        ));
    }

    public function getName() {
        return 'spectasonic_front_edit_user';
    }

}
