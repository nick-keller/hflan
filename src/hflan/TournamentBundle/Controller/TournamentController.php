<?php

namespace hflan\TournamentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use hflan\TournamentBundle\Entity\Event;
use hflan\TournamentBundle\Entity\Tournament;
use hflan\TournamentBundle\Form\TournamentType;
use hflan\TournamentBundle\Form\CasuType;

class TournamentController extends Controller
{
    /**
     * @Secure(roles="ROLE_RESPO")
     */
    public function newAction(Event $event, $type)
    {
        $tournament = new Tournament($type);
        $tournament->setEvent($event);

        if($type == 'default')
            $form = $this->createForm(new TournamentType, $tournament);
        else
            $form = $this->createForm(new CasuType, $tournament);

        $request = $this->get('request');
        if( $request->getMethod() == 'POST' )
        {
            $form->bind($request);
            if( $form->isValid() )
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($tournament);
                $em->flush();

                $this->get('session')->setFlash('success', "tournament.message.success.new_tournament");
                return $this->redirect( $this->generateUrl('hflan_tournament_show_event', array('slug' => $event->getSlug())) );
            }
            else
                $this->get('session')->setFlash('error', "message.error.form");
        }

        return $this->render('hflanTournamentBundle:Tournament:new.html.twig', array(
            'event' => $event,
            'form' => $form->createView(),
            'type' => $type,
        ));
    }

    /**
     * @Secure(roles="ROLE_RESPO")
     */
    public function showAction(Tournament $tournament)
    {
        $repository = $this->getDoctrine()
            ->getRepository('hflanTournamentBundle:Team');

        return $this->render('hflanTournamentBundle:Tournament:show.html.twig', array(
            'tournament' => $tournament,
            'confirmedTeams' => $repository->getConfirmed($tournament),
            'pendingTeams' => $repository->getPending($tournament),
        ));
    }

    /**
     * @Secure(roles="ROLE_RESPO")
     */
    public function editAction(Tournament $tournament)
    {
        if($tournament->getCasu())
            $form = $this->createForm(new CasuType, $tournament);
        else
        {
            $form = $this->createForm(new TournamentType, $tournament);

            $originalCustomFields = array();
            foreach($tournament->getCustomFields() as $field)
                $originalCustomFields[] = $field;
        }

        $request = $this->get('request');
        if( $request->getMethod() == 'POST' )
        {
            $form->bind($request);
            if( $form->isValid() )
            {
                foreach($tournament->getCustomFields() as $field)
                    foreach($originalCustomFields as $key => $toDel)
                        if($toDel->getId() === $field->getId())
                            unset($originalCustomFields[$key]);

                $em = $this->getDoctrine()->getManager();

                foreach($originalCustomFields as $field)
                    $em->remove($field);

                $em->persist($tournament);
                $em->flush();

                $this->get('session')->setFlash('success', 'tournament.message.success.edit');
                return $this->redirect( $this->generateUrl('hflan_tournament_show_tournament', array('slug'=>$tournament->getEvent()->getSlug(), 'id'=>$tournament->getId())) );
            }
            else
                $this->get('session')->setFlash('error', "message.error.form");
        }

        return $this->render('hflanTournamentBundle:Tournament:edit.html.twig', array(
            'form' => $form->createView(),
            'tournament' => $tournament,
            'type' => $tournament->getCasu() ? 'casu' : 'default',
        ));
    }
}