<?php
namespace Ibillmaker\Hub\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ModifyRepositoryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container
            ->findDefinition('sylius.repository.user')
            ->addMethodCall('setUserViaSecurityContext', array(
                new Reference('security.context'),
            ))
        ;
        $container
            ->findDefinition('sylius.repository.product')
            ->addMethodCall('setUserViaSecurityContext', array(
                new Reference('security.context'),
            ))
        ;
    }
}

?>

