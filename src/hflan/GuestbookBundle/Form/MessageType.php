<?php

namespace hflan\GuestbookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', null, array('label' => 'guestbook.field.author'))
            ->add('email', null, array( 'label' => 'guestbook.field.email', 'required' => false, 'attr' => array('placeholder' => 'facultatif')) )
            ->add('rating', null, array('label' => 'guestbook.field.rating', 'attr' => array('class' => 'star-meter')) )
            ->add('message', null, array('label' => 'guestbook.field.message'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'hflan\GuestbookBundle\Entity\Message'
        ));
    }

    public function getName()
    {
        return 'hflan_guestbookbundle_messagetype';
    }
}
