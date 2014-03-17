<?php
// src/Phil/UserBundle/Form/Type/UserType.php
namespace Phil\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text');
        $builder->add('firstname', 'text');
        $builder->add('lastname', 'text');
        $builder->add('birthday', 'birthday');
        $builder->add('email', 'email');
        $builder->add('plainPassword', 'repeated', array(
           'type'        => 'password',
           'invalid_message' => 'The password fields must match.',
            'first_options'  => array('label' => 'Password'),
            'second_options' => array('label' => 'Repeat Password'),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Phil\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'user';
    }
}
