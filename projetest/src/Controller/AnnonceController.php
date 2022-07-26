<?php
namespace App\Controller;

use App\Entity\Annonce;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AnnonceController extends AbstractController
{   
    /**
     * @Route("/index", name="annonce_index")
     */
    public function indexAction()
    {

    }

    /**
     * @Route("/add", name="annonce_add")
     */
    public function addAction()
    {
        $annonce = new Annonce();
        $annonce->setTitre("Développeur symfony");
        $annonce->setContenu("A la recherche de ...");

        $em = $this->getDoctrine()->getManager();
        $em->persist($annonce);
        $em->flush();

        return new Response($annonce);
    }
}