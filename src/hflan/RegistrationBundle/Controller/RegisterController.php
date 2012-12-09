<?php

namespace hflan\RegistrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;
use hflan\UserBundle\Form\UserType;
use hflan\TournamentBundle\Entity\Team;
use hflan\TournamentBundle\Entity\Player;
use hflan\TournamentBundle\Form\RegisterTeamType;
use hflan\TournamentBundle\Form\EditTeamType;
use hflan\TournamentBundle\Form\PlayerType;

class RegisterController extends Controller
{
    public function indexAction()
    {
        if($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            return $this->redirect( $this->generateUrl('hflan_home') );

        $nextEvent = $this->getDoctrine()->getRepository('hflanTournamentBundle:Event')->getNextEvent();

        if ($nextEvent == null ||
            !$nextEvent->getOpen() ||
            $nextEvent->getOpenAt() > new \DateTime() ||
            $nextEvent->getCloseAt() < new \DateTime())
        {
            return $this->render('hflanRegistrationBundle:Register:error.html.twig', array(
                'nextEvent' => $nextEvent,
            ));
        }

        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();

        $team = new Team;
        $team->setUser($user);

        $userForm = $this->createForm(new UserType, $user);
        $teamForm = $this->createForm(new RegisterTeamType($nextEvent), $team);

        $request = $this->get('request');
        if( $request->getMethod() == 'POST' )
        {
            $userForm->bind($request);
            $teamForm->bind($request);

            if( $userForm->isValid() && $teamForm->isValid() )
            {
                $user->setUsername($user->getEmail());
                $user->setEnabled(true);
                $userManager->updateUser($user);

                if(!$team->getName())
                    $team->setName('single player');

                $players = array();
                for($i=0; $i < $team->getTournament()->getPlayersPerTeam(); ++$i)
                {
                    $players[] = new Player();
                    $players[$i]->setTeam($team);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($team);

                foreach($players as $player)
                    $em->persist($player);

                $em->flush();

                $this->get('session')->setFlash('success', 'register.message.success.create_team');
                return $this->redirect( $this->generateUrl('hflan_registration_index') );
            }
            else
                $this->get('session')->setFlash('error', "message.error.form");
        }

        return $this->render('hflanRegistrationBundle:Register:index.html.twig', array(
            'userForm' => $userForm->createView(),
            'teamForm' => $teamForm->createView(),
        ));
    }

    /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function editAction()
    {
        if($this->container->get('security.context')->getToken()->getUser()->getTeam() === null)
            return $this->redirect( $this->generateUrl('hflan_home') );

        $teamForm = $this->createForm(new EditTeamType, $this->container->get('security.context')->getToken()->getUser()->getTeam());
        //$playerList = $this->container->get('security.context')->getToken()->getUser()->getTeam()->getPlayers();


        return $this->render('hflanRegistrationBundle:Register:edit.html.twig', array(
            'teamForm' => $teamForm->createView(),
            'tournament' => $this->container->get('security.context')->getToken()->getUser()->getTeam()->getTournament(),
            'team' => $this->container->get('security.context')->getToken()->getUser()->getTeam(),
        ));
    }
}
