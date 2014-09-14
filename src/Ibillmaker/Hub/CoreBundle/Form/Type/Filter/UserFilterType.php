<?php

/*
* This file is part of the Sylius package.
*
* (c) Nishant Patel
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace Ibillmaker\Hub\CoreBundle\Form\Type\Filter;

use Sylius\Bundle\CoreBundle\Form\Type\Filter\UserFilterType as BaseUserFilterType;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * User filter form type.
 *
 * @author Nishant Patel <ngpatel@outlook.com>
 */
class UserFilterType extends BaseUserFilterType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('type', 'text', array(
                'label' => 'sylius.form.user_filter.query',
                'attr'  => array(
                    'placeholder' => 'sylius.form.user_filter.query'
                )
            ))
         
        ;
    }

    public function getName()
    {
        return 'sylius_user_filter';
    }
}
