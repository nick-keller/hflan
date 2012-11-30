<?php

namespace hflan\TournamentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label'=>'tournament.field.name'))
            ->add('price', null, array('label'=>'tournament.field.price', 'attr'=>array('placeholder'=>'€')))
            ->add('start_at', 'datetime', array('label'=>'tournament.field.start_at', 'date_widget'=>'single_text', 'date_format'=>'dd/MM/yyyy'))
            ->add('end_at', 'datetime', array('label'=>'tournament.field.end_at', 'date_widget'=>'single_text', 'date_format'=>'dd/MM/yyyy'))
            ->add('public', null, array('label'=>'tournament.field.public', 'required'=>false))
            ->add('open_at', 'datetime', array('label'=>'tournament.field.open_at', 'date_widget'=>'single_text', 'date_format'=>'dd/MM/yyyy'))
            ->add('close_at', 'datetime', array('label'=>'tournament.field.close_at', 'date_widget'=>'single_text', 'date_format'=>'dd/MM/yyyy'))
            ->add('open', null, array('label'=>'tournament.field.open', 'required'=>false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'hflan\TournamentBundle\Entity\Event'
        ));
    }

    public function getName()
    {
        return 'hflan_tournamentbundle_eventtype';
    }
}
