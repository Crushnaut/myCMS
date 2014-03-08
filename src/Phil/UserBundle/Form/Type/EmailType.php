<?php
// src/Acme/AccountBundle/Form/Type/EmailType.php
namespace Phil\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email');
        $builder->add('Resend', 'submit');
    }

    public function getName()
    {
        return 'resend';
    }
}
