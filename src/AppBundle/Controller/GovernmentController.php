<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DonePost;
use AppBundle\Entity\InProgressPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class GovernmentController
 * @Route("/government")
 * @Security("has_role('ROLE_GOVERNMENT')")
 */
class GovernmentController extends Controller
{
    /*Promoting post to In Progress category  */
    /**
     * @Route("/set_in_progress/{post_id}")
     */
    public function setInProgressAction($post_id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')
            ->findOneBy(array('id' => $post_id));

        if ($post == null) {
            new JsonResponse(json_encode(array(
                'promoted' => false,
            )));
        }

        $inProgressPost = new InProgressPost();

        $post->setActive(false);

        $inProgressPost->setPost($post);
        $inProgressPost->setGovernment($this->getUser());
        $inProgressPost->setComment("Test");

        $em->merge($post);
        $em->persist($inProgressPost);
        $em->flush();

        return new JsonResponse(json_encode(array(
            'promoted' => true,
        )));
    }

    /*Promoting post to Done category*/
    /**
     * @Route("/set_is_done/{post_id}")
     */
    public function setIsDoneAction($post_id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:InProgressPost')
            ->findOneBy(array('post' => $post_id));

        if ($post == null) {
            new JsonResponse(json_encode(array(
                'promoted' => false,
            )));
        }

        $donePost = new DonePost();

        $post->setActive(false);

        $donePost->setPost($em->getReference('AppBundle:Post', $post_id));
        $donePost->setGovernment($this->getUser());

        $em->merge($post);
        $em->persist($donePost);
        $em->flush();

        return new JsonResponse(json_encode(array(
            'promoted' => true,
        )));
    }
}
