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
 * @Security("has_role('ROLE_ADMIN')")
 */
class AdminController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $formUser = $this->createFormBuilder()
            ->add('searchUser', TextType::class, array('attr' =>
                array('placeholder' => 'Search users')))
            ->getForm();

        $formPost = $this->createFormBuilder()
            ->add('searchPost', TextType::class, array('attr' =>
                array('placeholder' => 'Search posts')))
            ->getForm();
            
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')
            ->findAllOrderById(0);

        $users = $em->getRepository('AppBundle:User')
            ->findAll();

        return $this->render('admin.html.twig', array(
            'formUser' => $formUser->createView(),
            'formPost' => $formPost->createView(),
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

    /**
     * @Route("/promote/{username}")
     */
    public function promoteAction($username) {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(array(
                'username' => $username,
            ));

        if ($user == null)
            return new JsonResponse(json_encode(array(
                'promoted' => false
            )));

        $roles = $user->getRoles();

        if (in_array('ROLE_GOVERNMENT', $roles)) {
            $user->setRoles(array('ROLE_ADMIN', 'ROLE_GOVERNMENT'));
        } else if (in_array('ROLE_USER', $roles)) {
            $user->setRoles(array('ROLE_GOVERNMENT'));
        }

        $em->merge($user);
        $em->flush();

        return new JsonResponse(json_encode(array('promoted' => true)));
    }

    /**
     * @Route("/demote/{username}")
     */
    public function demoteAction($username) {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(array(
                'username' => $username,
            ));

        if ($user == null)
            return new JsonResponse(json_encode(array(
                'promoted' => false
            )));

        $roles = $user->getRoles();

        if (in_array('ROLE_GOVERNMENT', $roles)) {
            $user->setRoles(array('ROLE_USER'));
        } else if (in_array('ROLE_ADMIN', $roles)) {
            $user->setRoles(array('ROLE_USER'));
        }

        $em->persist($user);
        $em->flush();

        return new JsonResponse(json_encode(array('promoted' => true)));
    }

    /**
     * @Route("/delete/{username}")
     */
    public function deleteAction($username) {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(array(
                'username' => $username,
            ));

        if ($user == null)
            return new JsonResponse(json_encode(array(
                'deleted' => false
            )));

        $em->remove($user);
        $em->flush();

        return new JsonResponse(json_encode(array('deleted' => true)));
    }

    /**
     * @Route("/post_delete/{post_id}")
     */
    public function postDeleteAction($post_id) {
        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('AppBundle:Post')
            ->find($post_id);

        if ($post == null)
            return new JsonResponse(json_encode(array(
                'deleted' => false
            )));

        $em->remove($post);
        $em->flush();

        return new JsonResponse(json_encode(array('deleted' => true)));
    }
}
