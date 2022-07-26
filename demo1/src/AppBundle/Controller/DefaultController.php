<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Service\ArrandMessage;

class DefaultController extends Controller
{   
    private $message;

    public function __construct(ArrandMessage $message)
    {
        $this->message = $message;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {   
        $mess = $this->message->showMessage();
        dump($mess);
        return $this->render('default/index.html.twig');
    }
}
