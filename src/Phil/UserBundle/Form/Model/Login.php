<?php
// src/Phil/UserBundle/Form/Model/Login.php
namespace Phil\UserBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class login
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max = 25)
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max = 64)
     */
    private $password;

    private $rememberMe;

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getRememberMe()
    {
        return $this->rememberMe;
    }

    public function setRememberMe($rememberMe)
    {
        $this->rememberMe = (Boolean) $rememberMe;
    }
}
