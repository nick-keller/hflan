<?php

namespace hflan\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array( 'label' => 'blog.field.title' ))
            ->add('content', 'textarea', array( 'label' => 'blog.field.content', 'attr' => array('class' => 'txtEditor', 'style' => 'height:200px') ))
            ->add('lang', 'choice', array('label' => 'blog.field.lang', 'choices' => array('fr' => 'Français', 'en' => 'English')))
            ->add('published', 'checkbox', array('required' => false, 'label' => 'blog.field.visible'))
            ->add('file', null, array('label' => 'blog.field.image'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'hflan\BlogBundle\Entity\Article'
        ));
    }

    public function getName()
    {
        return 'hflan_blogbundle_articletype';
    }
}
