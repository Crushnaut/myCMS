<?php
// src/Acme/AccountBundle/Form/Type/ActivateType.php
namespace Phil\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ActivationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('activationCode', 'text');
        $builder->add('Activate', 'submit');
    }

    public function getName()
    {
        return 'activate';
    }
}
