<?php

namespace hflan\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\SecurityExtraBundle\Annotation\Secure;

class AdminController extends Controller
{
    /**
     * @Secure(roles="ROLE_STAFF")
     */
    public function dashboardAction()
    {
        return $this->render('hflanAdminBundle:Admin:dashboard.html.twig');
    }
}
