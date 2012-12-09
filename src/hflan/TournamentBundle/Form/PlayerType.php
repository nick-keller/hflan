<?php

namespace hflan\TournamentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, array('required'=>false))
            ->add('lastname', null, array('required'=>false))
            ->add('nickname', null, array('required'=>false))
            ->add('email', null, array('required'=>false))
            ->add('birthday', 'date', array('label'=>'tournament.field.birthday', 'widget'=>'single_text', 'date_format'=>'dd/MM/yyyy'))
            ->add('pc_type', 'choice', array('choices'=>array('Descktop', 'Laptop')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'hflan\TournamentBundle\Entity\Player'
        ));
    }

    public function getName()
    {
        return 'hflan_tournamentbundle_playertype';
    }
}
