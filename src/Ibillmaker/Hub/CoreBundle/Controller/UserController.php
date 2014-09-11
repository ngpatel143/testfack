<?php

namespace Ibillmaker\Hub\CoreBundle\Controller;

use Sylius\Bundle\CoreBundle\Controller\UserController as BaseUserController;

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
}


