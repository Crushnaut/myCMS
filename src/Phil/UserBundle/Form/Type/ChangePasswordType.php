<?php
// src/Acme/AccountBundle/Form/Type/ChangePasswordType.php
namespace Phil\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('plainPassword', 'repeated', array(
                    'first_name'  => 'password',
                    'second_name' => 'confirm',
                    'type'        => 'password'))
                ->add('Change Password', 'submit');
    }

    public function getName()
    {
        return 'changepassword';
    }
}