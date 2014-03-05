<?php
// src/Phil/UserBundle/Controller/UserController.php
namespace Phil\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use JMS\SecurityExtraBundle\Annotation\Secure;

use Phil\UserBundle\Form\Type\RegistrationType;
use Phil\UserBundle\Form\Type\ChangePasswordType;
use Phil\UserBundle\Form\Type\UpdateType;
use Phil\UserBundle\Form\Model\Registration;
use Phil\UserBundle\Form\Model\Password;
use Phil\UserBundle\Entity\User;
use Phil\UserBundle\Entity\Role;

class UserController extends Controller
{
    public function registerAction(Request $request)
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

            $this->get('session')->getFlashBag()->add('notice', 'You have successfully registered. You may now log-in.');

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

    /**
     * @Secure(roles="IS_AUTHENTICATED_FULLY, ROLE_USER")
     */
    public function changePasswordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $currentUser = $this->get('security.context')->getToken()->getUser();

        $form = $this->createForm(new ChangePasswordType(), $currentUser);
        $form->handleRequest($request);

        if ($form->isValid()) 
        {
            $em->persist($currentUser);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Your password was successfully changed.');
            return $this->redirect($this->generateUrl('user_update'));
        }

        return $this->render(
            'PhilUserBundle:User:changePassword.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Secure(roles="ROLE_USER")
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $currentUser = $this->get('security.context')->getToken()->getUser();

        $form = $this->createForm(new UpdateType(), $currentUser);
        $form->handleRequest($request);

        if ($form->isValid()) 
        {
            $em->persist($currentUser);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Your profile was successfully updated.');
            return $this->redirect($this->generateUrl('user_update'));
        }

        return $this->render(
            'PhilUserBundle:User:update.html.twig',
            array('form' => $form->createView())
        );
    }
}
