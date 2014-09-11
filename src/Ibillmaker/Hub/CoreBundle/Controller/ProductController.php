<?php

namespace Ibillmaker\Hub\CoreBundle\Controller;

use Sylius\Bundle\CoreBundle\Controller\ProductController as BaseProductController;

class ProductController extends BaseProductController
{

    /**
     * {@inheritdoc}
     */
    public function createNew()
    {
        if (null === $user= $this->get('security.context')->getToken()->getUser()) {
            throw new NotFoundHttpException('Invalid Request.');
        }
        $adminUser = $user->getAdmin();
        $product = parent::createNew();
        
        if($adminUser ==  NULL)
        {
            $adminId = $user->getId(); 
        }
        else{
            $adminId = $adminUser->getId();
        }
        $product->setAdminId($adminId);
        $product->setUser($user);

        return $product;
    }
}


