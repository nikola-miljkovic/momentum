<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\InProgressPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        $formSearch = $this->createFormBuilder()
            ->add('searchUser', TextType::class, array('attr' =>
                array('placeholder' => 'Search users')))
            ->add('searchPost', TextType::class, array('attr' =>
                array('placeholder' => 'Search posts')))
            ->getForm();

        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->findAllOrderById(0);

        $users = $em->getRepository('AppBundle:User')
            ->findAll();

        return $this->render('AppBundle:Admin:index.html.twig', array(
            'form' => $formSearch->createView(),
            'posts' => $posts,
            'users' => $users,
        ));
    }

    /**
     * @Route("/posts/{offset}")
     */
    public function postsAction($offset)
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->findAllOrderById(0);

        return new JsonResponse(json_encode($posts));
    }
}
