<?php
// src/Phil/UserBundle/Mailer/Mailer.php
namespace Phil\UserBundle\Mailer;

use Symfony\Component\Templating\EngineInterface;

use Phil\UserBundle\Entity\User;

class Mailer
{
    protected $mailer;

    protected $templating;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, array $parameters)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->parameters = $parameters;
    }

    /*
     * Method for sending an e-mail to a user containing their password reset code.
     */
    public function sendActivationEmail(User $user)
    {
        $template = $this->parameters['activation.template'];
        $rendered = $this->templating->render($template, array('user' => $user));
        $this->sendEmail($rendered, $this->parameters['fromemail'], $user->getEmail());
    }

    /*
     * Method for sending an e-mail to a user containing their password reset code.
     */
    public function sendPasswordResetEmail(User $user)
    {
        $template = $this->parameters['forgotpassword.template'];
        $rendered = $this->templating->render($template, array('user' => $user));
        $this->sendEmail($rendered, $this->parameters['fromemail'], $user->getEmail());
    }

    public function sendEmail($template, $fromEmail, $toEmail)
    {
        // use the first line as the subject, rest as the body
        $lines = explode("\n", trim($template));
        $subject = $lines[0];
        $body = implode("\n", array_slice($lines, 1));

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail)
            ->setBody($body);

        $this->mailer->send($message);
    }
}
