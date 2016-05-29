<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DonePost;
use AppBundle\Entity\InProgressPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class GovernmentController
 * @Route("/")
 */
class GovernmentController extends Controller
{
    /**
     * @Route("/set_in_progress/{post_id}")
     * @Security("is_granted('ROLE_GOVERNMENT')")
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

    /**
     * @Route("/set_is_done/{post_id}")
     * @Security("is_granted('ROLE_GOVERNMENT')")
     */
    public function setIsDoneAction($post_id)
    {
        $em = $this->getDoctrine()->getManager();

        $donePost = new DonePost();

        $donePost->setPost($em->getReference('AppBundle:Post', $post_id));
        $donePost->setGovernment($this->getUser());

        $em->persist($donePost);
        $em->flush();

        return $this->render('AppBundle:Admin:index.html.twig', array(
            "message" => "done"
        ));
    }
}
