<?php

use Sylius\Bundle\CoreBundle\Kernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Ibillmaker\Hub\CoreBundle\IbillmakerHubCoreBundle()
        );

        if (in_array($this->environment, array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
        }

        return array_merge(parent::registerBundles(), $bundles);
    }
}
