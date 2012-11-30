<?php

namespace hflan\InfoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;

class ContactController extends Controller
{
    public function indexAction()
    {
        return $this->render('hflanInfoBundle:Contact:index.html.twig', array(
        ));
    }

    public function menuAction()
    {
    }
}