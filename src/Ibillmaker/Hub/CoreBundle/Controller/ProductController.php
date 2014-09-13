<?php

namespace Ibillmaker\Hub\CoreBundle\Controller;

use Sylius\Bundle\CoreBundle\Controller\ProductController as BaseProductController;

/*
 * auther Nishant Patel
 * ngpatel@outlook.com
 */
class ProductController extends BaseProductController
{

    /**
     * {@inheritdoc}
     */
    public function createNew()
    {
        /* here we fetch adminUser for logged in user.
         * and we set admin user and user.
         */ 
        if (null === $user= $this->get('security.context')->getToken()->getUser()) {
            throw new NotFoundHttpException('Invalid Request.');
        }
        $adminUser = $user->getAdmin();
        $product = parent::createNew();
       
        // if adminId is null then he is an admin user. we set 
        $admin = ($adminUser ==  NULL)? $user : $adminUser;
        $product->setAdmin($admin);
        $product->setUser($user);
        
        return $product;
    }
}


