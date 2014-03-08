<?php
// src/Phil/UserBundle/Controller/SecurityController.php

namespace Phil\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

use Phil\UserBundle\Form\Type\LoginType;
use Phil\UserBundle\Form\Model\Login;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $this->get('session')->getFlashBag()->add('notice', 'You are already logged in.');
            return $this->redirect($this->generateUrl('user_update'));
        }
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        $lastLogin = new Login();
        $lastLogin->setUsername($session->get(SecurityContext::LAST_USERNAME));

        // this triggers if the user is being asked to re-authenticate
        if (is_null($lastLogin->getUsername()) && $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED') && !($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')))
        {
            $lastLogin->setUsername($this->get('security.context')->getToken()->getUser()->getUsername());
            $lastLogin->setRememberMe(true);
            $this->get('session')->getFlashBag()->add('notice', 'Before continuing with that action you must re-enter your password.');
        }

        $form = $this->createForm(new LoginType(), $lastLogin, array('action' => $this->generateURL('login_check')));

        return $this->render(
            'PhilUserBundle:Security:login.html.twig',
            array('error' => $error, 'form' => $form->createView())
        );
    }
}
