<?php
namespace Ibillmaker\Hub\CoreBundle\Form\Type;

use Sylius\Bundle\CoreBundle\Form\Type\UserType as BaseUserType;
use Symfony\Component\Form\FormBuilderInterface;
/**
 * this class is a child class of sylius UserType.php
 * here we will add new field while inserting new field in Registraion form.
 *
 * @author Nishant
 */
class UserType extends BaseUserType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('companyId', 'text', array('required' => true));
        $builder->add('companyName', 'text', array('required' => true));
    }
    
}