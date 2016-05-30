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
 * @Route("/")
 * @Security("has_role('ROLE_GOVERNMENT')")
 */
class GovernmentController extends Controller
{
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

        return new JsonResponse(json_encode(array(
            'promoted' => true,
        )));
    }

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

        $em->persist($post);
        $em->persist($donePost);
        $em->flush();

        return new JsonResponse(json_encode(array(
            'promoted' => true,
        )));
    }
}
