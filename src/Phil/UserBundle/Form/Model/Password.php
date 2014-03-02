<?php
// src/Phil/UserBundle/Form/Model/Password.php
namespace Phil\UserBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Password
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max = 64)
     */
    private $password;

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}
