<?php
// src/Phil/UserBundle/Controller/UserController.php
namespace Phil\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Phil\UserBundle\Form\Type\RegistrationType;
use Phil\UserBundle\Form\Model\Registration;

use Phil\UserBundle\Entity\User;
use Phil\UserBundle\Entity\Role;

use Phil\UserBundle\Form\Type\ChangePasswordType;
use Phil\UserBundle\Form\Model\Password;

use Phil\UserBundle\Form\Type\UpdateType;

class UserController extends Controller
{
    public function registerAction()
    {
        $registration = new Registration();
        $form = $this->createForm(new RegistrationType(), $registration, array(
            'action' => $this->generateUrl('user_create'),
        ));

        return $this->render(
            'PhilUserBundle:User:register.html.twig',
            array('form' => $form->createView())
        );
    }

    public function createAction(Request $request)
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

            return $this->redirect($this->generateUrl('login'));
        }

        return $this->render(
            'PhilUserBundle:User:register.html.twig',
            array('form' => $form->createView())
        );
    }

    public function menuAction()
    {
        return $this->render('PhilUserBundle:User:menu.html.twig');
    }

    public function changePasswordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new ChangePasswordType(), new Password());

        $form->handleRequest($request);

        if ($form->isValid()) 
        {
            $user = $this->get('security.context')->getToken()->getUser();

            if ((is_null($user)) || (!($this->get('security.context')->isGranted('ROLE_USER'))))
            {
                throw new AccessDeniedException();
            }

            $user->setPassword($form->getData()->getPassword());

            $em->persist($user);

            $em->flush();

            return $this->redirect($this->generateUrl('PhilCMSBundle_content_default'));
        }

        return $this->render(
            'PhilUserBundle:User:changePassword.html.twig',
            array('form' => $form->createView())
        );
    }

    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $currentUser = $this->get('security.context')->getToken()->getUser();

        $form = $this->createForm(new UpdateType(), $currentUser);

        $form->handleRequest($request);

        if ($form->isValid()) 
        {
            if ((is_null($currentUser)) || (!($this->get('security.context')->isGranted('ROLE_USER'))))
            {
                throw new AccessDeniedException();
            }

            $currentUser->setEmail($form->getData()->getEmail());
            $currentUser->setUsername($form->getData()->getUsername());

            $em->persist($currentUser);

            $em->flush();

            return $this->redirect($this->generateUrl('PhilCMSBundle_content_default'));
        }

        return $this->render(
            'PhilUserBundle:User:update.html.twig',
            array('form' => $form->createView())
        );
    }
}