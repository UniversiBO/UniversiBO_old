<?php

namespace Universibo\Bundle\WebsiteBundle;

use Universibo\Bundle\LegacyBundle\Auth\UniversiBOFactory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class UniversiboWebsiteBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new UniversiBOFactory());
    }
}
