<?php
/**
 * Created by PhpStorm.
 * User: Myloose
 * Date: 24/09/2016
 * Time: 21:56
 */
namespace Spectasonic\Back\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FM\ElfinderBundle\Form\Type\ElFinderType;

class MediasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', new ElFinderType(), array(
                    'label' => 'back.blog.medias.form.label_image',
                    'required' => true,
                    'instance' => 'form_image',
                    'enable' => true
                ))
            ->add('link', 'url', array(
                'label' => 'back.blog.medias.form.label_link',
                'required' => false
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Spectasonic\Back\BlogBundle\Entity\Medias'
        ));
    }
    public function getBlockPrefix()
    {
        return 'spectasonic_back_blogbundle_medias';
    }


}