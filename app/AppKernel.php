<?php

use Symfony\Component\ClassLoader\DebugUniversalClassLoader;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Debug\ErrorHandler;
use Symfony\Component\HttpKernel\Debug\ExceptionHandler;
use Sylius\Bundle\CoreBundle\Kernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle(),
            new Ibillmaker\Hub\CoreBundle\IbillmakerHubCoreBundle()
        );

        return array_merge($bundles, parent::registerBundles());
    }
}
