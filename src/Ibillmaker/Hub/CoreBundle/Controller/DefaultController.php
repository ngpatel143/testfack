<?php

namespace Ibillmaker\Hub\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('IbillmakerHubCoreBundle:Default:index.html.twig', array('name' => $name));
    }
}
