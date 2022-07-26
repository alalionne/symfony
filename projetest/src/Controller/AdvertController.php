<?php
namespace App\Controller;

use App\Entity\Annonce;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends AbstractController
{  
    
    public function indexAction(){
        $entity = new Advert();
        $entity->setTitre("Développeur web");
        $entity->setContent("Nous à la recherche de...");

        $image1 = new Image();
        $image1->setUrl('image1.pn');
        $image1->setAlt('image1');

        $entity->setImage($image1);
         die('ici');
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);

        $em->flsuh();

        return new Response("<div>c'est fait</div>");
    }
}