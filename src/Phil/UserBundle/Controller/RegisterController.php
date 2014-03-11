<?php
// src/Phil/UserBundle/Controller/RegisterController.php

namespace Phil\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Phil\UserBundle\Form\Model\Registration;
use Phil\UserBundle\Form\Type\RegistrationType;

use Phil\UserBundle\Entity\User;

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

        if ($form->isValid()) 
        {
            $user = $form->getData()->getUser();
            $role = $em->getRepository('PhilUserBundle:Role')->findOneByRole('ROLE_USER');
            $user->addRole($role);

            $em->persist($user);
            $em->flush();

            $this->sendActivationEmail($user);

            $this->get('session')->getFlashBag()->add('notice', 'You have successfully registered. You have been sent an e-mail containing a code to activate your account. Enter it below before you can login.');

            return $this->redirect($this->generateUrl('user_activate'));
        }

        return $this->render('PhilUserBundle:User:register.html.twig', array('form' => $form->createView()));
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
}
