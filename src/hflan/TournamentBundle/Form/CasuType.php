<?php

namespace hflan\TournamentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CasuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label'=>'tournament.field.name'))
            ->add('nbrTeams', null, array('label'=>'tournament.field.nbrVisitor'))
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
        return 'hflan_tournamentbundle_casutype';
    }
}
