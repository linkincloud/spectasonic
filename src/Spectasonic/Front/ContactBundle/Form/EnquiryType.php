<?php
// src/Spectasonic/Front/ContactBundle/Form/EnquiryType.php

namespace Spectasonic\Front\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EnquiryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('firstname');
        $builder->add('email', 'email');
        $builder->add('Title');
        $builder->add('message');
        {
            return 'contact';
        }
    }
}