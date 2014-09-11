<?php
namespace Ibillmaker\Hub\CoreBundle\Form\Type;

use Sylius\Bundle\CoreBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       parent::buildForm($builder, $options);
      /* $builder->add('companyId', 'text', array('required' => true));
        $builder->add('companyName', 'text', array('required' => true)); 
        $builder->remove('firstName'); */
    }

    public function getName()
    {
        return 'sylius_user_registration';
    }
}
