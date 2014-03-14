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

use Phil\UserBundle\Mailer\Mailer;

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

        // if a activation code is not specified in the URL or by the form, display the form
        if (is_null($activationCode))
        {
            return $this->render('PhilUserBundle:Activate:activateForm.html.twig', array('form' => $form->createView()));
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('PhilUserBundle:User')->findOneByActivationCode($activationCode);

        // if a user does not match up with an activation code then the code isn't valid
        if (is_null($user))
        {   
            $this->get('session')->getFlashBag()->add('notice', "That is not a valid activation code.");
            return $this->render('PhilUserBundle:Activate:activateForm.html.twig', array('form' => $form->createView()));
        }

        // if we pass all these checks then we can activate the user
        $user->activate();
        $em->persist($user);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', "" . $user->getUsername() ." has been activated. Please login.");
        return $this->redirect($this->generateUrl('login'));
}
    
    /*
     *  Action called when the resend activation code form is viewed or submitted.
     */
    public function resendAction(Request $request)
    {
        $form = $this->createForm(new EmailType(), new Email());
        $form->handleRequest($request);

        // if the form is not valid or there are errors display the form
        if ($form->isValid() === false)
        {
            $title = "Resend Activation Code";
            return $this->render('PhilUserBundle:User:emailForm.html.twig', array('form' => $form->createView(), 'title' => $title));
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('PhilUserBundle:User')->findOneByEmail($form->getData()->getEmail());

        // if an email does not match a user then the email is not valid
        if (is_null($user))
        {
            $this->get('session')->getFlashBag()->add('notice', 'Specified e-mail address does not correspond with a user in our database.');
            $title = "Resend Activation Code";
            return $this->render('PhilUserBundle:User:emailForm.html.twig', array('form' => $form->createView(), 'title' => $title));
        }

        // if the user is enabled then there is no need to send an activation email
        if ($user->isEnabled())
        {
            $this->get('session')->getFlashBag()->add('notice', 'That user has already been activated.');
            $title = "Resend Activation Code";
            return $this->render('PhilUserBundle:User:emailForm.html.twig', array('form' => $form->createView(), 'title' => $title));
        }
        
        // if we pass all the above tests we can send the activation email
        $mailer = $this->get('user_mailer');
        $mailer->sendActivationEmail($user);

        $this->get('session')->getFlashBag()->add('notice', 'You have been sent an e-mail containing a code to activate your account. Enter it below before you can login.');
        return $this->redirect($this->generateUrl('user_activate', array('userID' => $user->getID())));
    }
}
