<?php
// src/Phil/UserBundle/Form/Model/Activation.php
namespace Phil\UserBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Activation
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max = 64)
     * @Assert\Length(min = 64)
     */
    private $activationCode;

    public function getActivationCode()
    {
        return $this->activationCode;
    }

    public function setActivationCode($activationCode)
    {
        $this->activationCode = $activationCode;
    }
}
