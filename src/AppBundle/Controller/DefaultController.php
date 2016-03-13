<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="_index")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('index.html.twig');
    }
     
     /**
      * @Route("/popular", name="_popular")
      */
     public function popularAction(Request $request)
     {
        return $this->render('index.html.twig');
     }
     
     /**
      * @Route("/active", name="_active")
      */
     public function activeAction(Request $request)
     {
        return $this->render('index.html.twig');
     }
     
     /**
      * @Route("/done", name="_done")
      */
     public function doneAction(Request $request)
     {
        return $this->render('index.html.twig');
     }
}
