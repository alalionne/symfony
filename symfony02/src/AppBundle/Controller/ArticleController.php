<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    /**
     * @Route("/articles/create", name="article_create")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $data = $request->getContent();
        $article = $this->get('jms_serializer')->deserialize($data,'AppBundle\Entity\Article','json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return new Response('',Response::HTTP_CREATED);
    }

    /**
     * @Route("/articles/{id}",name="article_show")
     * @Method({"GET"})
     */
    public function showAction(Article $article)
    {
        $data = $this->get('jms_serializer')->serialize($article,'json', SerializationContext::create()->setGroups(array('detail')));

        $response = new Response($data);
        $response->headers->set('Content-Type','application/json');

        return $response;
    }

    /**
    * @Route("/articles", name="article_list")
    * @Method({"GET"})
    */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('AppBundle:Article')->findAll();

        $data = $this->get('jms_serializer')->serialize($articles,'json', SerializationContext::create()->setGroups(array('list')));

        $response = new Response($data);
        $response->headers->set('Content-Type','application/json');

        return $response;
    }
}
