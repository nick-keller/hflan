<?php

namespace hflan\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array('label'=>'user.field.email', 'attr'=>array('title'=>'user.field.message.email')))
            ->add('plainPassword', 'password', array('label'=>'user.field.password', 'attr'=>array('title'=>'user.field.message.password')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'hflan\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'hflan_userbundle_usertype';
    }
}
