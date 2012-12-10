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
            ->add('firstname', null, array('label'=>'tournament.field.firstname', 'required'=>false))
            ->add('lastname', null, array('label'=>'tournament.field.lastname', 'required'=>false))
            ->add('nickname', null, array('label'=>'tournament.field.nickname', 'required'=>false))
            ->add('email', null, array('label'=>'tournament.field.email', 'required'=>false))
            ->add('birthday', 'date', array('label'=>'tournament.field.birthday', 'required'=>false, 'widget'=>'single_text', 'format'=>'dd/MM/yyyy', 'attr'=>array('placeholder'=>'ex: 31/12/1995')))
            ->add('pc_type', 'choice', array('label'=>'tournament.field.pcType', 'choices'=>array('Descktop'=>'Descktop', 'Laptop'=>'Laptop'), 'attr'=>array('style'=>'width:178px')))
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
