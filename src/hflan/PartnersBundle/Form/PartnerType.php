<?php

namespace hflan\PartnersBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PartnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'partners.field.name'))
            ->add('description', 'textarea', array('label' => 'partners.field.description'))
            ->add('url', 'text', array('label' => 'partners.field.url'))
            ->add('sort_index', null, array('label' => 'partners.field.priority'))
            ->add('file', 'file', array('label' => 'partners.field.logo', 'required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'hflan\PartnersBundle\Entity\Partner'
        ));
    }

    public function getName()
    {
        return 'hflan_partnersbundle_partnertype';
    }
}
