<?php

namespace Spectasonic\Back\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FM\ElfinderBundle\Form\Type\ElFinderType;

class BlogType extends AbstractType {

    private $role = 'ROLE_EDITEUR';

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('author', 'entity', array(
                    'label' => 'back.blog.edit.form.label_author',
                    'class' => 'SpectasonicFrontUserBundle:User',
                    'query_builder' => function (EntityRepository $er) {
                        return $er
                                ->createQueryBuilder('u')
                                ->where('u.roles LIKE :roles')
                                ->orderBy('u.username', 'ASC')
                                ->setParameter('roles', '%"' . $this->role . '"%');
                    },
                    'multiple' => false,
                    'expanded' => false
                ))
                ->add('title', 'text', array(
                    'label' => 'back.blog.edit.form.label_title'
                ))
                ->add('excerpt', 'text', array(
                    'label' => 'back.blog.edit.form.label_excerpt'
                ))
                ->add('content', 'ckeditor', array(
                    'label' => 'back.blog.edit.form.label_content'
                ))
                ->add('published', 'checkbox', array(
                    'label' => 'back.blog.edit.form.label_published',
                    'required' => false
                ))
                ->add('page', 'checkbox', array(
                    'label' => 'back.blog.edit.form.label_page',
                    'required' => false
                ))
                ->add('mainimage', new ElFinderType(), array(
                    'label' => 'back.blog.edit.form.label_mainimage',
                    'required' => true,
                    'instance' => 'form_image',
                    'enable' => true
                ))
                ->add('slider', 'entity', array(
                    'required' => false,
                    'label' => 'back.blog.edit.form.label_slider',
                    'class' => 'SpectasonicBackBlogBundle:Slider',
                    'property' => 'name'
                ))
                ->add('document', new ElFinderType(), array(
                    'label' => 'back.blog.edit.form.label_document',
                    'required' => false,
                    'instance' => 'form_document',
                    'enable' => true
                ))
                ->add('categories', 'entity', array(
                    'label' => 'back.blog.edit.form.label_category',
                    'class' => 'SpectasonicBackBlogBundle:BlogCategory',
                    'property' => 'name',
                    'multiple' => true
                ))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Spectasonic\Back\BlogBundle\Entity\Blog'
        ));
    }

    public function getBlockPrefix() {
        return 'spectasonic_back_blogbundle_blog';
    }

}
