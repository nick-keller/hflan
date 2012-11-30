<?php

namespace hflan\TournamentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \hflan\TournamentBundle\Form\PlayerType;

class EditTeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label'=>'tournament.field.team_name', 'required'=>false, 'attr'=>array('title'=>'tournament.field.message.team_name')))
            ->add('players', 'collection', array('type'=>new PlayerType()))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'hflan\TournamentBundle\Entity\Team'
        ));
    }

    public function getName()
    {
        return 'hflan_tournamentbundle_teamtype';
    }
}
