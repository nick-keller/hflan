<?php

namespace hflan\InfoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;

class InfoController extends Controller
{
    public function indexAction()
    {
        return $this->render('hflanInfoBundle:Info:index.html.twig', array(
        ));
    }

    public function menuAction()
    {
    }
}