<?php

namespace Ibillmaker\Hub\CoreBundle\Controller;

use Sylius\Bundle\CoreBundle\Controller\UserController as BaseUserController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class UserController extends BaseUserController
{

    /**
     * {@inheritdoc}
     */
    public function createNew()
    {
        if (null === $adminUser= $this->get('security.context')->getToken()->getUser()) {
            throw new NotFoundHttpException('Invalid Request.');
        }
        $user = parent::createNew();
        $user->setAdmin($adminUser);

        return $user;
    }
    
//    public function createNewContactAction(Request $request)
//    {
//        $userRepository = $this->container->get('sylius.repository.user');
//        $user = $userRepository->createNew();
//        
//        
//    }
 
}


