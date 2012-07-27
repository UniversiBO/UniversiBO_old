<?php
namespace Universibo\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Reference;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ChannelTypeCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('universibo_core.routing.channel')) {
            return;
        }

        $definition = $container->getDefinition('universibo_core.routing.channel');

        foreach ($container->findTaggedServiceIds('universibo_core.channel.type') as $id => $attributes) {
            $definition->addMethodCall('register', array(new Reference($id)));
        }
    }
}
