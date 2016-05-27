<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\InProgressPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class AdminController
 * @Route("/admin")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class AdminController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Admin:index.html.twig', array(
            // ...
        ));
    }
    
    /**
     * @Route("/set_in_progress/{post_id}")
     */
    public function setInProgressAction($post_id) 
    {
        $em = $this->getDoctrine()->getManager();

        $inProgressPost = new InProgressPost();

        $inProgressPost->setPost($em->getReference('AppBundle:Post', $post_id));
        $inProgressPost->setGovernment($this->getUser());
        $inProgressPost->setComment("Test");

        $em->persist($inProgressPost);
        $em->flush();

        return $this->render('AppBundle:Admin:index.html.twig', array(
            "message" => "done"
        ));
    }

}
