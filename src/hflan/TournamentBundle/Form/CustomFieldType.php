<?php

namespace hflan\TournamentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label'=>'tournament.field.name'))
            ->add('validation', null, array('label'=>'tournament.field.validation'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'hflan\TournamentBundle\Entity\CustomField'
        ));
    }

    public function getName()
    {
        return 'hflan_tournamentbundle_customfieldtype';
    }
}
