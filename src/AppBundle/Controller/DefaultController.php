<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Query\QueryException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


class DefaultController extends Controller
{
    /*Routing to index page and putting all posts sorted by date*/
    /**
     * @Route("/", name="_index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('index.html.twig', array(
            'postSource' => 'post_list_new'
        ));
    }

    /*Clicking on popular and calling ajax controller */
    /**
     * @Route("/popular", name="_popular")
     */
    public function popularAction(Request $request)
    {
        return $this->render('index.html.twig', array(
            'postSource' => 'post_list_popular'
        ));
    }

    /*routing to Active == In Progress*/
    /**
     * @Route("/active", name="_active")
     */
    public function activeAction(Request $request)
    {
        return $this->render('index.html.twig', array(
            'postSource' => 'post_list_active'
        ));
    }
    /*Routing to done posts and calling AjaxController to render Done posts*/
    /**
     * @Route("/done", name="_done")
     */
    public function doneAction(Request $request)
    {
        return $this->render('index.html.twig', array(
            'postSource' => 'post_list_done'
        ));
    }
    /*On clicking Log in, rendering that page*/
    /**
     * @Route("/login", name="_login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    /*On clicking Register, rendering that page, making form for registration,
      If form filled correctly adding new user in database of users
    */
    /**
     * @Route("/register", name="_register")
     * @Method({"POST", "GET"})
     */
    public function registerAction(Request $request)
    {
        $user = new User();

        $form = $this->createFormBuilder($user,array(
            'validation_groups' => array('registration'),
            ))
            ->add('firstName', TextType::class, array('attr' =>
                array('placeholder' => 'First name')))
            ->add('lastName', TextType::class, array('attr' =>
                array('placeholder' => 'Last name')))
            ->add('username', TextType::class, array('attr' =>
                array('placeholder' => 'Username')))
            ->add('email', EmailType::class, array('attr' =>
                array('placeholder' => 'Email')))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Your passwords do not match, please try again.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options' => array('attr' =>
                    array('placeholder' => 'Password')),
                'second_options' => array('attr' =>
                    array('placeholder' => 'Re-enter password'))))
            ->add('submit', SubmitType::class, array('label' => 'Register'))
            ->getForm();

        if ($request->isMethod('GET')) {
            return $this->render('register.html.twig', array(
                'form' => $form->createView(),
            ));
        }

        $form->handleRequest($request);

        /*Handling with correctly filled form*/
        if ($form->isValid()) {
            try {
                // encode password
                $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $user->getPassword());
                $user->setPassword($password);

                // persist
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->render('register.html.twig', array(
                    'form' => $form->createView(),
                ));
            } catch(UniqueConstraintViolationException $exception) {
                return $this->render('register.html.twig', array(
                    'form' => $form->createView(),
                    'form_errors' => 'Username or email is already in use.',
                ));
            }
        } else {
            return $this->render('register.html.twig', array(
                'form' => $form->createView(),
                'form_errors' => $form->getErrors(true),
            ));
        }
    }

}
