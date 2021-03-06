<?php
// src/Phil/UserBundle/Form/Model/Registration.php
namespace Phil\UserBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

use Phil\UserBundle\Entity\User;

class Registration
{
    /**
     * @Assert\Type(type="Phil\UserBundle\Entity\User")
     * @Assert\Valid()
     */
    private $user;

    /**
     * @Assert\NotBlank()
     * @Assert\True()
     */
    private $termsAccepted;

    private $captcha;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setTermsAccepted($termsAccepted)
    {
        $this->termsAccepted = (Boolean) $termsAccepted;
    }

    public function getTermsAccepted()
    {
        return $this->termsAccepted;
    }

    public function setCaptcha($captcha)
    {
        $this->captcha = $captcha;
    }

    public function getCaptcha()
    {
        return $this->captcha;
    }
}