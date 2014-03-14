<?php
// src/Phil/UserBundle/Mailer/Mailer.php
namespace Phil\UserBundle\Mailer;

use Symfony\Component\Templating\EngineInterface;

use Phil\UserBundle\Entity\User;

class Mailer
{
    protected $mailer;

    protected $templating;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /*
     * Method for sending an e-mail to a user containing their password reset code.
     */
    public function sendActivationEmail(User $user)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Registration successful! Please confirm e-mail.')
            ->setFrom('philsymfony@gmail.com')
            ->setTo($user->getEmail())
            ->setBody($this->templating->render('PhilUserBundle:Email:activation.txt.twig', array('user' => $user)));

        $this->mailer->send($message);
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
            ->setBody($this->templating->render('PhilUserBundle:Email:forgotpassword.txt.twig', array('user' => $user)));

        $this->mailer->send($message);
    }
}
