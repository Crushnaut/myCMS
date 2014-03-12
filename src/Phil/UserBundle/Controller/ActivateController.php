<?php
// src/Phil/UserBundle/Controller/ActivateController.php

namespace Phil\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Phil\UserBundle\Form\Type\ActivationType;
use Phil\UserBundle\Form\Type\EmailType;
use Phil\UserBundle\Form\Model\Activation;
use Phil\UserBundle\Form\Model\Email;

use Phil\UserBundle\Entity\User;

class ActivateController extends Controller
{
    /*
     * Action called when the activate user page is viewed or the form is submitted.
     */
    public function activateAction($activationCode = null, Request $request)
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

            if (is_null($user) === true)
            {   
                $this->get('session')->getFlashBag()->add('notice', "That is not a valid activation code.");
            }

            if (false === $user->isEnabled())
            {   
                $user->activate();
                $em->persist($user);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', "" . $user->getUsername() ." has been activated. Please login.");

                return $this->redirect($this->generateUrl('login'));
            }
        }

        return $this->render('PhilUserBundle:Activate:activateForm.html.twig', array('form' => $form->createView()));
    }
    
    /*
     *  Action called when the resend activation code form is viewed or submitted.
     */
    public function resendAction(Request $request)
    {
        $form = $this->createForm(new EmailType(), new Email());
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('PhilUserBundle:User')->findOneByEmail($form->getData()->getEmail());

            if (is_null($user) === false)
            {
                if ($user->isEnabled())
                {
                    $this->get('session')->getFlashBag()->add('notice', 'That user has already been activated.');
                    $title = "Resend Activation Code";
                    return $this->render('PhilUserBundle:User:emailForm.html.twig', array('form' => $form->createView(), 'title' => $title));
                }
                
                $this->sendActivationEmail($user);
                $this->get('session')->getFlashBag()->add('notice', 'You have been sent an e-mail containing a code to activate your account. Enter it below before you can login.');
                return $this->redirect($this->generateUrl('user_activate', array('userID' => $user->getID())));
            } 

            $this->get('session')->getFlashBag()->add('notice', 'Specified e-mail address does not correspond with a user in our database.');
        }

        $title = "Resend Activation Code";
        return $this->render('PhilUserBundle:User:emailForm.html.twig', array('form' => $form->createView(), 'title' => $title));
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
            ->setBody($this->renderView('PhilUserBundle:Email:activation.txt.twig', array('user' => $user)));

        $this->get('mailer')->send($message);
    }
}
