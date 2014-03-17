<?php
// src/Phil/UserBundle/Controller/ForgotPasswordController.php

namespace Phil\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Phil\UserBundle\Form\Type\EmailType;
use Phil\UserBundle\Form\Type\ChangePasswordType;
use Phil\UserBundle\Form\Model\Email;

use Phil\UserBundle\Entity\User;

use Phil\UserBundle\Mailer\Mailer;

class ForgotPasswordController extends Controller
{
    /*
     *  Action called when the forgot password form is viewed or submitted
     */
    public function emailAction(Request $request)
    {
        $form = $this->createForm(new EmailType(), new Email());
        $form->handleRequest($request);

        // if the form hasn't been posted yet or there are errors, display the form to collect the data
        if ($form->isValid() === false)
        {
            $title = "Forgot Password Request";
            return $this->render('PhilUserBundle:User:emailForm.html.twig', array('form' => $form->createView(), 'title' => $title));
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('PhilUserBundle:User')->findOneByEmail($form->getData()->getEmail());

        // if a user isn't found then the e-mail address is not valid
        if (is_null($user))
        {
            $this->get('session')->getFlashBag()->add('notice', 'Specified e-mail address does not correspond with a user in our database.');
            $title = "Forgot Password Request";
            return $this->render('PhilUserBundle:User:emailForm.html.twig', array('form' => $form->createView(), 'title' => $title));
        }

        // if the password is expired and it is too soon since the last reset
        if ($user->isPasswordExpired() && $user->getResetExpiryTime('U') > time())
        {
            $this->get('session')->getFlashBag()->add('notice', 'A password reset has already been sent to that e-mail. Please check this e-mail for a link to reset your password. A new password can be requsted in ' . round(($user->getResetExpiryTime('U') - time()) / 60) . " minutes.");
            return $this->redirect($this->generateUrl('user_forgotPassword'));
        }

        // if we make it past all the checks above it is okay to begin the reset the password process by sending an email and flagging the account as password expired
        $user->expirePassword();

        $mailer = $this->get('user_mailer');
        $mailer->sendPasswordResetEmail($user);

        $em->persist($user);
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'You have been sent an e-mail containing a link to reset your password.');
        return $this->redirect($this->generateUrl('user_forgotPassword'));   
    }

    /*
     *  Action called when a user submits a forgot password reset link and code or submits a forgot password reset form
     */
    public function resetAction($resetCode, Request $request)
    {
        // if a reset code is not specified re-direct to the forgot password request form
        if (is_null($resetCode))
        {
            return $this->redirect($this->generateUrl('user_forgotPassword'));
        }

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('PhilUserBundle:User')->findOneByResetCode($resetCode);

        // if a user is not found then the reset code is not valid
        if (is_null($user))
        {   
            $this->get('session')->getFlashBag()->add('notice', "That is not a valid password reset code.");
            return $this->redirect($this->generateUrl('user_forgotPassword'));
        }

        // if the reset code was requested too long ago then the user must request a new code
        if ($user->getResetExpiryTime('U') < time())
        {
            $user->clearPasswordReset();
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', "That password reset code has expired.");
            return $this->redirect($this->generateUrl('user_forgotPassword'));
        }

        $form = $this->createForm(new ChangePasswordType(), $user);
        $form->handleRequest($request);

        // if we pass all the above checks then we can display the password reset form and process the reset if the form data is valid
        if ($form->isValid()) 
        {
            $user->clearPasswordReset();
            $user->setPassword();
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Your password was successfully changed.');
            return $this->redirect($this->generateUrl('login'));
        }

        return $this->render('PhilUserBundle:User:changePasswordForm.html.twig', array('form' => $form->createView()));
    }    
}
