<?php

namespace hflan\TournamentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use hflan\TournamentBundle\Entity\Event;
use hflan\TournamentBundle\Form\EventType;

class EventController extends Controller
{
    /**
     * @Secure(roles="ROLE_RESPO")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()
            ->getRepository('hflanTournamentBundle:Event');

        $events = $repository->findBy(array(), array('start_at'=>'ASC'));

        return $this->render('hflanTournamentBundle:Event:index.html.twig', array(
            'events' => $events,
        ));
    }

    /**
     * @Secure(roles="ROLE_RESPO")
     */
    public function newAction()
    {
        $event = new Event();

        $form = $this->createForm(new EventType, $event);

        $request = $this->get('request');
        if( $request->getMethod() == 'POST' )
        {
            $form->bind($request);
            if( $form->isValid() )
            {
                $event->setSlug( $this->container->get('hflan_blog.slugify')->slugify($event->getName()) );
                $em = $this->getDoctrine()->getManager();
                $em->persist($event);
                $em->flush();

                $this->get('session')->setFlash('success', "tournament.message.success.new_event");
                return $this->redirect( $this->generateUrl('hflan_tournament_show_event', array('slug'=>$event->getSlug())) );
            }
            else
                $this->get('session')->setFlash('error', "message.error.form");
        }

        return $this->render('hflanTournamentBundle:Event:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Secure(roles="ROLE_RESPO")
     */
    public function editAction(Event $event)
    {
        $form = $this->createForm(new EventType, $event);

        $request = $this->get('request');
        if( $request->getMethod() == 'POST' )
        {
            $form->bind($request);
            if( $form->isValid() )
            {
                $event->setSlug( $this->container->get('hflan_blog.slugify')->slugify($event->getName()) );
                $em = $this->getDoctrine()->getManager();
                $em->persist($event);
                $em->flush();

                $this->get('session')->setFlash('success', 'tournament.message.success.edit');
                return $this->redirect( $this->generateUrl('hflan_tournament_show_event', array('slug'=>$event->getSlug())) );
            }
            else
                $this->get('session')->setFlash('error', "message.error.form");
        }

        return $this->render('hflanTournamentBundle:Event:edit.html.twig', array(
            'form' => $form->createView(),
            'event' => $event,
        ));
    }

    /**
     * @Secure(roles="ROLE_RESPO")
     */
    public function deleteAction(Event $event)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();

        $this->get('session')->setFlash('success', 'tournament.message.success.delete');
        return $this->redirect( $this->generateUrl('hflan_tournament_index') );
    }

    /**
     * @Secure(roles="ROLE_RESPO")
     */
    public function showAction(Event $event)
    {
        $repository = $this->getDoctrine()
            ->getRepository('hflanTournamentBundle:Tournament');

        $tournaments = $repository->findByEvent($event);

        return $this->render('hflanTournamentBundle:Event:show.html.twig', array(
            'event' => $event,
            'tournaments'=>$tournaments,
        ));
    }

    public function menuAction()
    {
        $repository = $this->getDoctrine()
            ->getRepository('hflanTournamentBundle:Event');

        $nextEvent = $repository->getNextEvent();

        return $this->render('hflanTournamentBundle:Event:menu.html.twig', array(
            'nextEvent' => $nextEvent,
        ));
    }

    public function liveAction()
    {
        $repEvent = $this->getDoctrine()
            ->getRepository('hflanTournamentBundle:Event');

        $tournaments = $this->getDoctrine()
            ->getRepository('hflanTournamentBundle:Tournament')
            ->getTournamentWithEmbeddedPlayer($repEvent->getCurrentEvent());

        return $this->render('hflanTournamentBundle:Event:live.html.twig', array(
            'tournaments' => $tournaments,
        ));
    }
}
