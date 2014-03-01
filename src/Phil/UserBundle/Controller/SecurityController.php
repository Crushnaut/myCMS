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

        $form = $this->createForm(new LoginType(), $lastLogin, array('action' => $this->generateURL('login_check')));

        return $this->render(
            'PhilUserBundle:Security:login.html.twig',
            array('error' => $error, 'form' => $form->createView())
        );
    }
}
