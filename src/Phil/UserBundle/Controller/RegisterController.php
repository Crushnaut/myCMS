<?php
// src/Phil/UserBundle/Controller/RegisterController.php

namespace Phil\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Phil\UserBundle\Form\Model\Registration;
use Phil\UserBundle\Form\Type\RegistrationType;

use Phil\UserBundle\Entity\User;

use Phil\UserBundle\Mailer\Mailer;

class RegisterController extends Controller
{
    /*
     * Action called when the register form is viewed and submitted.
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new RegistrationType(), new Registration());
        $form->handleRequest($request);

        // if the form is not valid or has not been submitted display it
        if ($form->isValid() === false) 
        {
            return $this->render('PhilUserBundle:Register:registerForm.html.twig', array('form' => $form->createView()));
        }

        $user = $form->getData()->getUser();

        $role = $em->getRepository('PhilUserBundle:Role')->findOneByRole($this->container->getParameter('user.register.defaultRole'));
        $user->addRole($role);
        $user->initializeActivationCode();

        $mailer = $this->get('user_mailer');
        $mailer->sendActivationEmail($user);

        $em->persist($user);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'You have successfully registered. You have been sent an e-mail containing a code to activate your account. Enter it below before you can login.');

        return $this->redirect($this->generateUrl('user_activate'));
    }
}
