<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
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

use AppBundle\ViewModel\LogIn;
use AppBundle\ViewModel\Register;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


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

    /**
     * @Route("/login", name="_login")
     * @Method({"POST", "GET"})
     */
    public function loginAction(Request $request)
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class, array('label' => 'Email'))
            ->add('password', PasswordType::class)
            ->add('submit', SubmitType::class, array('label' => 'Log In'))
            ->getForm();

        if ($request->isMethod('GET')) {
            return $this->render('login.html.twig', array(
                'form' => $form->createView(),
            ));
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$token = new UsernamePasswordToken($user, $user->getPassword(), 'main', array());
            $token = new UsernamePasswordToken($user, $user->getPassword(), 'main');
            $token->
            $this->get('security.token_storage')->setToken($token);

            // Fire the login event
            // Logging the user in above the way we do it doesn't do this automatically
            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);

            if ($token->isAuthenticated()) {
                return $this->redirectToRoute('_index');
            } else {
                return $this->render('login.html.twig', array(
                    'form' => $form->createView(),
                    'form_errors' => $form->getErrors(true),
                ));
            }
        } else {
            return $this->render('login.html.twig', array(
                'form' => $form->createView(),
                'form_errors' => $form->getErrors(true),
            ));
        }
    }


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

        if ($form->isValid()) {
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
        } else {
            return $this->render('register.html.twig', array(
                'form' => $form->createView(),
                'form_errors' => $form->getErrors(true),
            ));
        }
    }

}
