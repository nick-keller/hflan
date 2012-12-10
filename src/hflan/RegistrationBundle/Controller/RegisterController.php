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
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Regex;

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
                $customFields = array();

                foreach($team->getTournament()->getCustomFields() as $field)
                    $customFields[$field->getName()] = '';

                for($i=0; $i < $team->getTournament()->getPlayersPerTeam(); ++$i)
                {
                    $players[] = new Player();
                    $players[$i]->setTeam($team);
                    $players[$i]->setCustomFields($customFields);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($team);

                foreach($players as $player)
                    $em->persist($player);

                $em->flush();

                $this->get('session')->setFlash('success', 'register.message.success.create_team');
                return $this->redirect( $this->generateUrl('fos_user_security_login') );
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
        $team = $this->container->get('security.context')->getToken()->getUser()->getTeam();

        if($team === null)
            return $this->redirect( $this->generateUrl('hflan_home') );

        $teamForm = $this->createForm(new EditTeamType, $team);

        $request = $this->get('request');
        if( $request->getMethod() == 'POST' )
        {
            $teamForm->bind($request);
            if( $teamForm->isValid() )
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($team);
                $em->flush();

                $this->get('session')->setFlash('success', 'register.message.success.teamEdit');
                return $this->redirect( $this->generateUrl('hflan_registration_edit') );
            }
            else
                $this->get('session')->setFlash('error', "message.error.form");
        }


        return $this->render('hflanRegistrationBundle:Register:edit.html.twig', array(
            'teamForm' => $teamForm->createView(),
            'tournament' => $this->container->get('security.context')->getToken()->getUser()->getTeam()->getTournament(),
            'team' => $this->container->get('security.context')->getToken()->getUser()->getTeam(),
        ));
    }

    /**
     * @Secure(roles="IS_AUTHENTICATED_REMEMBERED")
     */
    public function playerEditAction(Player $player)
    {
        $team = $this->container->get('security.context')->getToken()->getUser()->getTeam();

        if($team === null)
            return $this->redirect( $this->generateUrl('hflan_home') );

        if($team->getId() !== $player->getTeam()->getId())
            return $this->redirect( $this->generateUrl('hflan_registration_edit') );

        $playerForm     = $this->createForm(new PlayerType, $player);
        $defaultData    = array();
        $constraints    = array();
        $playerFields   = $player->getCustomFields();
        $slugify        = $this->container->get('hflan_blog.slugify');
        $customFieldRep = $this->getDoctrine()->getRepository('hflanTournamentBundle:CustomField');

        foreach($playerFields as $key => $value)
        {
            $defaultData[  $slugify->slugify($key)  ] = $value;
            $constraints[  $slugify->slugify($key)  ] = new Regex(array( 'pattern' =>  '#'.$customFieldRep->findOneByName($key)->getValidation().'#'  ));
        }

        $fieldsForm = $this->createFormBuilder(
            $defaultData,
            array( 'constraints' => new Collection($constraints) )
        );

        foreach($playerFields as $key => $value)
            $fieldsForm->add(
                $slugify->slugify($key),
                'text',
                array('label'=>$key, 'required'=>false)
            );

        $fieldsForm = $fieldsForm->getForm();

        $request = $this->get('request');
        if( $request->getMethod() == 'POST' )
        {
            $playerForm->bind($request);
            $fieldsForm->bind($request);

            if( $playerForm->isValid() && $fieldsForm->isValid() )
            {
                $data = $fieldsForm->getData();

                foreach($playerFields as $key => $value)
                    $playerFields[$key] = $data[$slugify->slugify($key)];

                $player->setCustomFields($playerFields);

                $em = $this->getDoctrine()->getManager();
                $em->persist($player);
                $em->flush();

                $this->get('session')->setFlash('success', 'register.message.success.playerEdit');
                return $this->redirect( $this->generateUrl('hflan_registration_edit') );
            }
            else
                $this->get('session')->setFlash('error', "message.error.form");
        }

        return $this->render('hflanRegistrationBundle:Register:playerEdit.html.twig', array(
            'form' => $playerForm->createView(),
            'customFields' => $fieldsForm->createView(),
        ));
    }

    /**
     * @Secure(roles="ROLE_RESPO")
     */
    public function showAction(Team $team)
    {
        return $this->render('hflanRegistrationBundle:Register:show.html.twig', array(
            'team' => $team,
        ));
    }

    /**
     * @Secure(roles="ROLE_RESPO")
     */
    public function deleteAction(Team $team)
    {
        $tournament = $team->getTournament();

        if($team->getPaid())
        {
            $this->get('session')->setFlash('error', 'register.message.error.team_paid');

            return $this->redirect( $this->generateUrl('hflan_tournament_show_tournament', array(
                'slug'=>$tournament->getEvent()->getSlug(),
                'id'=>$tournament->getId(),
            )) );
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($team);
        $em->flush();

        $this->get('session')->setFlash('success', 'register.message.success.delete');

        return $this->redirect( $this->generateUrl('hflan_tournament_show_tournament', array(
            'slug'=>$tournament->getEvent()->getSlug(),
            'id'=>$tournament->getId(),
        )) );
    }

    /**
     * @Secure(roles="ROLE_RESPO")
     */
    public function validAction(Team $team)
    {
        $team->setConfirmed(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($team);
        $em->flush();

        $this->get('session')->setFlash('success', 'register.message.success.valid');

        return $this->redirect( $this->generateUrl('hflan_registration_show', array(
            'id'=>$team->getId(),
        )) );
    }
}
