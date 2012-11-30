<?php

namespace hflan\PartnersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
use hflan\PartnersBundle\Entity\Partner;
use hflan\PartnersBundle\Form\PartnerType;

class PartnersController extends Controller
{
    public function indexAction()
    {
        $repository = $this->getDoctrine()
            ->getRepository('hflanPartnersBundle:Partner');

        $partners = $repository->findBy(array(), array('sort_index'=>'ASC'));

        return $this->render('hflanPartnersBundle:Partners:index.html.twig', array(
            'partners' => $partners
        ));
    }

    /**
     * @Secure(roles="ROLE_COM")
     */
    public function newAction()
    {
        $partner = new Partner;

        $form = $this->createForm(new PartnerType, $partner);

        $request = $this->get('request');
        if( $request->getMethod() == 'POST' )
        {
            $form->bind($request);
            if( $form->isValid() )
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($partner);
                $em->flush();

                $this->get('session')->setFlash('success', "partners.message.success.new");
                return $this->redirect( $this->generateUrl('hflan_partners_index') );
            }
        }

        return $this->render('hflanPartnersBundle:Partners:new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Secure(roles="ROLE_COM")
     */
    public function adminAction()
    {
        $repository = $this->getDoctrine()
            ->getRepository('hflanPartnersBundle:Partner');

        $partners = $repository->findBy(array(), array('sort_index'=>'ASC'));

        $form = $this->createFormBuilder(array())
            ->add('batch_action', 'choice', array(
            'choices' => array(
                'delete' => $this->get('translator')->trans('partners.action.delete')),
            'label' => 'partners.action.selection'));

        foreach($partners as $partner)
            $form->add(''.$partner->getId(), 'checkbox', array('required' => false));

        $form = $form->getForm();

        $request = $this->get('request');
        if ($request->getMethod() == 'POST')
        {
            $form->bind($request);
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();

            foreach($partners as $partner)
            {
                if($data[$partner->getId()] == 1)
                {
                    if($data['batch_action'] == 'delete')
                        $em->remove($partner);
                }
            }

            $em->flush();
            $messages = array(
                'delete' => "partners.message.success.delete"
            );
            $this->get('session')->setFlash('success', $messages[ $data['batch_action'] ]);
            return $this->redirect( $this->generateUrl('hflan_partners_admin') );
        }

        return $this->render('hflanPartnersBundle:Partners:admin.html.twig', array(
            'partners' => $partners,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Secure(roles="ROLE_COM")
     */
    public function editAction(Partner $partner)
    {
        $form = $this->createForm(new PartnerType(), $partner);

        $request = $this->get('request');
        if( $request->getMethod() == 'POST' )
        {
            $form->bind($request);
            if( $form->isValid() )
            {
                $partner->preUpload();
                $em = $this->getDoctrine()->getManager();
                $em->persist($partner);
                $em->flush();

                $this->get('session')->setFlash('success', 'partners.message.success.update');
                return $this->redirect( $this->generateUrl('hflan_partners_index') );
            }
            else
                $this->get('session')->setFlash('error', "message.error.form");
        }
        return $this->render('hflanPartnersBundle:Partners:edit.html.twig', array(
            'form' => $form->createView(),
            'partner' => $partner
        ));
    }

    public function menuAction()
    {
        $repository = $this->getDoctrine()
            ->getRepository('hflanPartnersBundle:Partner');

        $partners = $repository->findBy(array(), array('sort_index'=>'ASC'));

        return $this->render('hflanPartnersBundle:Partners:menu.html.twig', array(
            'partners' => $partners
        ));
    }
}