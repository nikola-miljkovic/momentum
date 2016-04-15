<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AjaxController
 * @Route("/ajax")
 */
class AjaxController extends Controller
{

    /**
    * @Route("/post", name="post")
    * @Method({"POST"})
    */
    public function postAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $post = new Post();
        $form = $this->createFormBuilder($post, array(
            'csrf_protection' => false
            ))
            ->add('content', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $post->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return new JsonResponse(array('message' => 'SUCCESS!'));
        } else {
            return new JsonResponse(array('message' => $form->getErrors(true)), 200);
        }
    }
}
