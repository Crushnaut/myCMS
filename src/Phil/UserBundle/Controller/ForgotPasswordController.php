<?php
// src/Phil/UserBundle/Controller/ForgotPasswordController.php

namespace Phil\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ForgotPasswordController extends Controller
{
    /*
     *  Action called when the forgot password form is viewed or submitted
     */
    public function forgotPasswordAction(Request $request)
    {
        $form = $this->createForm(new EmailType(), new Email());
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('PhilUserBundle:User')->findOneByEmail($form->getData()->getEmail());

            if ($user)
            {
                if ($user->isPasswordExpired())
                {   
                    $timeUntilNextRequest = $user->getResetTime()->format('U') + 3600 - time();
                    if ($timeUntilNextRequest > 0)
                    {
                        $this->get('session')->getFlashBag()->add('notice', 'A password reset has already been sent to that e-mail. Please check this e-mail for a link to reset your password. A new password can be requsted in ' . round($timeUntilNextRequest / 60) . " minutes.");
                        return $this->redirect($this->generateUrl('user_forgotPassword'));
                    }
                    
                }
                $user->setPasswordExpired(true);
                $user->setResetTime(new \DateTime());
                $user->resetResetCode();
                $this->sendPasswordResetEmail($user);

                $em->persist($user);
                $em->flush();

                $this->get('session')->getFlashBag()->add('notice', 'You have been sent an e-mail containing a link to reset your password.');
                return $this->redirect($this->generateUrl('user_forgotPassword'));
            } 
            else
            {
                $this->get('session')->getFlashBag()->add('notice', 'Specified e-mail address does not correspond with a user in our database.');
            }
        }
        $title = "Forgot Password Request";
        return $this->render('PhilUserBundle:User:collectEmail.html.twig', array('form' => $form->createView(), 'title' => $title));
    }

    /*
     * Helper method for sending an e-mail to a user containing their password reset code.
     */
    public function sendPasswordResetEmail(User $user)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('A password reset has been requested')
            ->setFrom('philsymfony@gmail.com')
            ->setTo($user->getEmail())
            ->setBody($this->renderView('PhilUserBundle:Email:forgotpassword.txt.twig', array('user' => $user)));

        $this->get('mailer')->send($message);
    }

    /*
     *  Action called when a user submits a forgot password reset link and code or submits a forgot password reset form
     */
    public function forgotPasswordResetAction($resetCode, Request $request)
    {
        if ($resetCode !== null)
        {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('PhilUserBundle:User')->findOneByResetCode($resetCode);

            if ($user === null)
            {   
                $this->get('session')->getFlashBag()->add('notice', "That is not a valid password reset code.");
                return $this->redirect($this->generateUrl('user_forgotPassword'));
            }

            if ($user->isPasswordExpired())
            {
                if ($user->getResetTime()->format('U') + 3600 < time())
                {
                    $this->get('session')->getFlashBag()->add('notice', "That password reset code has expired.");

                    $user->setResetTime(null);
                    $user->setResetCode(null);
                    $user->setPasswordExpired(false);
                    $em->persist($user);
                    $em->flush();

                    return $this->redirect($this->generateUrl('user_forgotPassword'));
                }

                $form = $this->createForm(new ChangePasswordType(), $user);
                $form->handleRequest($request);

                if ($form->isValid()) 
                {
                    $user->setResetTime(null);
                    $user->setResetCode(null);
                    $user->setPasswordExpired(false);
                    $em->persist($user);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('notice', 'Your password was successfully changed.');
                    return $this->redirect($this->generateUrl('login'));
                }

                return $this->render('PhilUserBundle:User:changePassword.html.twig', array('form' => $form->createView()));
            }
        }
        return $this->redirect($this->generateUrl('user_forgotPassword'));
    }    
}
