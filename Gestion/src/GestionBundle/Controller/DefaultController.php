<?php

namespace GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DefaultController extends Controller
{    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/",name="gestion_dashboard")
     */
    public function indexAction()
    { 
        return $this->render('GestionBundle:Default:index.html.twig');
    }
}
