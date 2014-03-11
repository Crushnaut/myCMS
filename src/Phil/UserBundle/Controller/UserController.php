<?php
// src/Phil/UserBundle/Controller/UserController.php
namespace Phil\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Phil\UserBundle\Form\Type\ChangePasswordType;
use Phil\UserBundle\Form\Type\UpdateType;

use Phil\UserBundle\Entity\User;
use Phil\UserBundle\Entity\Role;

class UserController extends Controller
{   
    /**
     * The action called when the change password form is viewed or submitted.
     * Only accessible if the user is fully authenticated.
     */
    public function changePasswordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.context')->getToken()->getUser();

        $form = $this->createForm(new ChangePasswordType(), $user);
        $form->handleRequest($request);

        if ($form->isValid()) 
        {
            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Your password was successfully changed.');
            return $this->redirect($this->generateUrl('user_update'));
        }

        return $this->render('PhilUserBundle:User:changePasswordForm.html.twig', array('form' => $form->createView()));
    }

    /**
     * The action called when the update user form is viewed or submitted.
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.context')->getToken()->getUser();

        //$originalEmail = $user->getEmail();

        $form = $this->createForm(new UpdateType(), $user);
        $form->handleRequest($request);

        if ($form->isValid()) 
        {
            /*if ($originalEmail !== $user->getEmail())
            {
                $user->setEnabled(false);
                $user->resetActivationCode();
                $this->get('session')->getFlashBag()->add('notice', 'Since you changed your e-mail you will have to reverify your account. Check your e-mail for this new code.');
                $this->sendActivationEmail($user);
            }*/

            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('notice', 'Your profile was successfully updated.');
            return $this->redirect($this->generateUrl('user_update'));
        }
        return $this->render('PhilUserBundle:User:controlpanelForm.html.twig', array('form' => $form->createView()));
    }
}
