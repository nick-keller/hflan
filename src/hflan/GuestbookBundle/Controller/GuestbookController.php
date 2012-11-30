<?php

namespace hflan\GuestbookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use hflan\GuestbookBundle\Entity\Message;
use hflan\GuestbookBundle\Form\MessageType;
use JMS\SecurityExtraBundle\Annotation\Secure;

class GuestbookController extends Controller
{
    public function indexAction($page)
    {
        // new messages
        $message = new Message;

        if($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $user= $this->get('security.context')->getToken()->getUser();
            if($user->getTeam())
                $message->setAuthor($user->getTeam()->getName());
            else
                $message->setAuthor($user->getUsername());
        }

        $form = $this->createForm(new MessageType, $message);

        $request = $this->get('request');
        if( $request->getMethod() == 'POST' )
        {
            $form->bind($request);
            if( $form->isValid() )
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($message);
                $em->flush();

                $this->get('session')->setFlash('success', "guestbook.message.success.new");
                return $this->redirect( $this->generateUrl('hflan_guestbook_index') );
            }
            else
                $this->get('session')->setFlash('error', "message.error.form");
        }

        // existing messages
        $repository = $this->getDoctrine()
            ->getRepository('hflanGuestbookBundle:Message');

        $messages = $repository->getPage($page, $this->container->getParameter('messages_per_page'));

        if($messages === false)
            throw $this->createNotFoundException('La page '.$page.' est inexistante.');

        return $this->render('hflanGuestbookBundle:Guestbook:index.html.twig', array(
            'form' => $form->createView(),
            'messages' => $messages,
            'page' => $page,
            'nb_pages' => $repository->getTotalPages($this->container->getParameter('messages_per_page'))
        ));
    }

    /**
     * @Secure(roles="ROLE_GUESTBOOK")
     */
    public function adminAction($page)
    {
        $repository = $this->getDoctrine()
            ->getRepository('hflanGuestbookBundle:Message');

        $messages = $repository->getPage($page, $this->container->getParameter('messages_per_page_admin'));

        if($messages === false)
            throw $this->createNotFoundException('La page '.$page.' est inexistante.');

        $form = $this->createFormBuilder(array())
            ->add('batch_action', 'choice', array(
            'choices' => array(
                'delete' => $this->get('translator')->trans('guestbook.action.delete')),
            'label' => 'guestbook.action.selection'));

        foreach($messages as $msg)
            $form->add(''.$msg->getId(), 'checkbox', array('required' => false));

        $form = $form->getForm();

        $request = $this->get('request');
        if ($request->getMethod() == 'POST')
        {
            $form->bind($request);
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();

            foreach($messages as $msg)
            {
                if($data[$msg->getId()] == 1)
                {
                    if($data['batch_action'] == 'delete')
                        $em->remove($msg);
                }
            }

            $em->flush();
            $messages = array(
                'delete' => "guestbook.message.success.delete"
            );
            $this->get('session')->setFlash('success', $messages[ $data['batch_action'] ]);
            return $this->redirect( $this->generateUrl('hflan_guestbook_admin', array('page' => 1)) );
        }

        return $this->render('hflanGuestbookBundle:Guestbook:admin.html.twig', array(
            'messages' => $messages,
            'page' => $page,
            'nb_pages' => $repository->getTotalPages($this->container->getParameter('messages_per_page')),
            'form' => $form->createView(),
        ));
    }
}
