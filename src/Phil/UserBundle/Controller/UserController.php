<?php
// src/Phil/UserBundle/Controller/UserController.php
namespace Phil\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use JMS\SecurityExtraBundle\Annotation\Secure;

use Phil\UserBundle\Form\Type\RegistrationType;
use Phil\UserBundle\Form\Type\ChangePasswordType;
use Phil\UserBundle\Form\Type\UpdateType;
use Phil\UserBundle\Form\Type\ActivationType;
use Phil\UserBundle\Form\Type\EmailType;
use Phil\UserBundle\Form\Model\Registration;
use Phil\UserBundle\Form\Model\Password;
use Phil\UserBundle\Form\Model\Activation;
use Phil\UserBundle\Form\Model\Email;
use Phil\UserBundle\Entity\User;
use Phil\UserBundle\Entity\Role;

class UserController extends Controller
{   
    /*
     * Action called when the register form is viewed and submitted.
     */
    public function registerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new RegistrationType(), new Registration());

        $form->handleRequest($request);

        if ($form->isValid()) 
        {
            $registration = $form->getData();
            $user = $registration->getUser();

            $role = $em->getRepository('PhilUserBundle:Role')
                        ->findOneByRole('ROLE_USER');

            $user->addRole($role);

            $em->persist($user);
            $em->flush();

            $this->sendActivationEmail($user);

            $this->get('session')->getFlashBag()->add('notice', 'You have successfully registered. You have been sent an e-mail containing a code to activate your account. Enter it below before you can login.');

            return $this->redirect($this->generateUrl('user_activate'));
        }

        return $this->render(
            'PhilUserBundle:User:register.html.twig',
            array('form' => $form->createView())
        );
    }

    /*
     * Helper method for sending an e-mail to a user containing their activation code.
     */
    public function sendActivationEmail(User $user)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Registration successful! Please confirm e-mail.')
            ->setFrom('philsymfony@gmail.com')
            ->setTo($user->getEmail())
            ->setBody($this->renderView('PhilUserBundle:Email:register.txt.twig', array('user' => $user)));

        $this->get('mailer')->send($message);
    }
    
    /*
     * Action called when the activate user page is viewed or the form is submitted.
     */
    public function activateUserAction($activationCode = null, Request $request)
    {

        $form = $this->createForm(new ActivationType(), new Activation());
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $activationCode = $form->getData()->getActivationCode();
        }

        if ($activationCode !== null)
        {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('PhilUserBundle:User')->findOneByActivationCode($activationCode);

            if ($user === null)
            {   
                $this->get('session')->getFlashBag()->add('notice', "That is not a valid activation code.");
            }

            if ($user && (false === $user->isEnabled()))
            {   
                $user->setEnabled(true);
                $user->setActivationCode(null);

                $em->persist($user);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', "" . $user->getUsername() ." has been activated. Please login.");

                return $this->redirect($this->generateUrl('login'));
            }
        }

        return $this->render('PhilUserBundle:User:activate.html.twig', array('form' => $form->createView()));
    }
    
    /*
     *  Action called when the resend activation code form is viewed or submitted.
     */
    public function resendActivationAction(Request $request)
    {
        $form = $this->createForm(new EmailType(), new Email());
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('PhilUserBundle:User')->findOneByEmail($form->getData()->getEmail());

            if ($user)
            {
                if ($user->isEnabled())
                {
                    $this->get('session')->getFlashBag()->add('notice', 'That user has already been activated.');
                    return $this->render('PhilUserBundle:User:resendActivation.html.twig', array('form' => $form->createView()));
                }

                $this->sendActivationEmail($user);
                
                $this->get('session')->getFlashBag()->add('notice', 'You have been sent an e-mail containing a code to activate your account. Enter it below before you can login.');

                return $this->redirect($this->generateUrl('user_activate', array('userID' => $user->getID())));
            } 
            else
            {
                $this->get('session')->getFlashBag()->add('notice', 'Specified e-mail address does not correspond with a user in our database.');
            }
        }

        return $this->render('PhilUserBundle:User:resendActivation.html.twig', array('form' => $form->createView()));
    }
    
    /*
     *  Action for generating the user menu.
     */
    public function menuAction()
    {
        return $this->render('PhilUserBundle:User:menu.html.twig');
    }

    /**
     * The action called when the change password form is viewed or submitted.
     */
    public function changePasswordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $currentUser = $this->get('security.context')->getToken()->getUser();

        $form = $this->createForm(new ChangePasswordType(), $currentUser);
        $form->handleRequest($request);

        if ($form->isValid()) 
        {
            $em->persist($currentUser);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Your password was successfully changed.');
            return $this->redirect($this->generateUrl('user_update'));
        }

        return $this->render(
            'PhilUserBundle:User:changePassword.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * The action called when the update user form is viewed or submitted.
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $currentUser = $this->get('security.context')->getToken()->getUser();

        $form = $this->createForm(new UpdateType(), $currentUser);
        $form->handleRequest($request);

        if ($form->isValid()) 
        {
            $em->persist($currentUser);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Your profile was successfully updated.');
            return $this->redirect($this->generateUrl('user_update'));
        }

        return $this->render(
            'PhilUserBundle:User:update.html.twig',
            array('form' => $form->createView())
        );
    }
}
