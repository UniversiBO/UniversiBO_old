<?php
namespace Universibo\Bundle\CoreBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use Universibo\Bundle\CoreBundle\DependencyInjection\Compiler\ChannelTypeCompilerPass;

class UniversiboCoreBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ChannelTypeCompilerPass());
    }
}
