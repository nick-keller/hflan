<?php

namespace hflan\TournamentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use hflan\TournamentBundle\Entity\TournamentRepository;
use hflan\TournamentBundle\Entity\Event;

class RegisterTeamType extends AbstractType
{
    private $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label'=>'tournament.field.team_name', 'attr'=>array('title'=>'tournament.field.message.team_name')))
            ->add('tournament', 'entity', array(
                'label'=>'tournament.field.tournament',
                'class'=>'hflanTournamentBundle:Tournament',
                'query_builder' => function(TournamentRepository $er) {
                    return $er->getTournaments($this->event);
                }
            ))
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
