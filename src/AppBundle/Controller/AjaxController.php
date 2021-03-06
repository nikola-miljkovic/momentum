<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\Vote;
use Doctrine\ORM\Query\QueryException;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
    /*Action on posting new post*/
    /**
    * @Route("/post", name="post")
    * @Method({"POST"})
    * @Security("is_granted('ROLE_USER') or is_granted('ROLE_GOVERNMENT')")
    */
    public function postAction(Request $request)
    {
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

        // TODO: Fix response messages
        if ($form->isValid()) {
            $post->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return new JsonResponse(json_encode($post));
        } else {
            return new JsonResponse(json_encode(array(
                'message' => "Post requires minimum of 40 and maximum of 400 characters."))
                , 200);
        }
    }

    /*Action on clicking Home, all posts sorted by date created on*/
    /**
     * @Route("/post_list_new/{offset}", defaults={"offset" = 0}, name="post_list_new")
     * @Method({"GET"})
     */
    public function postListAction(int $offset)
    {
        $em = $this->getDoctrine()->getManager();

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $user = $this->getUser();
            $posts = $em->getRepository('AppBundle:Post')->findAllWithVotedOrderById($offset, $user);
        }
        else
        {
            $posts = $em->getRepository('AppBundle:Post')->findAllOrderById($offset);
        }

        return new JsonResponse(json_encode($posts));
    }
    /*Action performed on clicking Popular, all posts sorted by number of votes*/
    /**
     * @Route("/post_list_popular/{offset}", defaults={"offset" = 0}, name="post_list_popular")
     * @Method({"GET"})
     */
    public function postListPopularAction(int $offset)
    {
        $em = $this->getDoctrine()->getManager();

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $posts = $em->getRepository('AppBundle:Post')->findAllWithVotedOrderByVoteCount($offset, $this->getUser());
        }
        else
        {
            $posts = $em->getRepository('AppBundle:Post')->findAllOrderByVoteCount($offset);
        }

        return new JsonResponse(json_encode($posts));
    }
    /*Action performed on clicking In Progress, all posts selected as In Progress, sorted by date of put in progress*/
    /**
     * @Route("/post_list_active/{offset}", defaults={"offset" = 0}, name="post_list_active")
     * @Method({"GET"})
     */
    public function postListActiveAction(int $offset)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em
            ->getRepository('AppBundle:InProgressPost')
            ->findActiveInProgressPostsOrderByDate($offset);
        return new JsonResponse(json_encode($posts));
    }
    /*Action on clicking Done, all posts selected as done, sorted by date of done*/
    /**
     * @Route("/post_list_done/{offset}", defaults={"offset" = 0}, name="post_list_done")
     * @Method({"GET"})
     */
    public function postListDoneAction(int $offset)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em
            ->getRepository('AppBundle:DonePost')
            ->findDonePostsOrderByDate($offset);
        return new JsonResponse(json_encode($posts));
    }

    /*Action on clicking vote up on post, if voted, down-vote */
    /**
     * @Route("/post_vote/{post_id}", name="post_vote", requirements={
     *         "post_id": "\d+"
     *     }
     *  )
     * @Method({"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function postVoteAction($post_id)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();

        try {
            $vote = $em
                ->getRepository('AppBundle:Vote')
                ->findOneBy(
                    array(
                        "user" => $this->getUser()->getId(),
                        "post" => $post_id,
                    )
                );

            if ($vote != null)
            {
                $vote->setActive(!$vote->getActive());
            }
            else
            {
                $vote = new Vote();
                $vote->setUser($this->getUser());

                $post = $em->getReference('AppBundle:Post', $post_id);
                $vote->setPost($post);
            }

            $em->persist($vote);
            $em->flush();
            $em->getConnection()->commit();
        } catch (Exception $e) {
            $em->getConnection()->rollBack();
        }

        $post = $em->getRepository('AppBundle:Post')
            ->findOneWithVote($post_id, $this->getUser());

        return new JsonResponse(json_encode($post));
    }

    /**
     * @Route("/post_delete/{post_id}", name="post_delete", requirements={
     *         "post_id": "\d+"
     *     }
     *  )
     * @Method({"POST"})
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function postDeleteAction($post_id) {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository("AppBundle:Post")->find($post_id);

        if ($post->getUser()->getId() == $this->getUser()->getId()) {
            // can delete
            $em->remove($post);
            $em->flush();
            return new JsonResponse(json_encode(array(
                "deleted" => true
            )));
        }

        return new JsonResponse(json_encode(array(
            "deleted" => false
        )));
    }
}
