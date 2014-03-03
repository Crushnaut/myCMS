<?php
// src/Acme/AccountBundle/Form/Type/LoginType.php
namespace Phil\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text');
        $builder->add('password', 'password');
        $builder->add('rememberMe', 'checkbox', array('required' => false));
        $builder->add('Login', 'submit');
    }

    public function getName()
    {
        return 'login';
    }
}
