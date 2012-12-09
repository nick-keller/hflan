<?php

namespace hflan\TournamentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TournamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label'=>'tournament.field.name'))
            ->add('game', null, array('label'=>'tournament.field.game'))
            ->add('nbrTeams', null, array('label'=>'tournament.field.nbrTeams'))
            ->add('playersPerTeam', null, array('label'=>'tournament.field.playersPerTeam'))
            ->add('price', null, array('label'=>'tournament.field.price', 'attr'=>array('placeholder'=>'€')))
            ->add('embeddedPlayer', null, array('label'=>'tournament.field.embeddedPlayer', 'required'=>false))
            ->add('customFields', 'collection', array('label'=>'tournament.field.customFields', 'type' => new CustomFieldType(), 'allow_add'  => true, 'allow_delete' => true, 'by_reference' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'hflan\TournamentBundle\Entity\Tournament'
        ));
    }

    public function getName()
    {
        return 'hflan_tournamentbundle_tournamenttype';
    }
}
