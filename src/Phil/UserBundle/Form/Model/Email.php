<?php
// src/Phil/UserBundle/Form/Model/Email.php
namespace Phil\UserBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Email
{
    /**
     * @Assert\NotBlank(message = "You must enter an email")
     * @Assert\Length(max = 64)
     * @Assert\Email()
     */
    private $email;

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }
}
