<?php
// src/Phil/UserBundle/Controller/ActivateController.php

namespace Phil\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Phil\UserBundle\Form\Type\ActivationType;
use Phil\UserBundle\Form\Type\EmailType;
use Phil\UserBundle\Form\Model\Activation;
use Phil\UserBundle\Form\Model\Email;

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
    public function resendAction(Request $request)
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
        $title = "Resend Activation Code";
        return $this->render('PhilUserBundle:User:collectEmail.html.twig', array('form' => $form->createView(), 'title' => $title));
    }    
}
