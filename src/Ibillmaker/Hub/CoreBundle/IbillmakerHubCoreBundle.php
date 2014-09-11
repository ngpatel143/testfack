<?php

namespace Ibillmaker\Hub\CoreBundle;

use Ibillmaker\Hub\CoreBundle\DependencyInjection\Compiler\ModifyRepositoryPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class IbillmakerHubCoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ModifyRepositoryPass());
    }
}
