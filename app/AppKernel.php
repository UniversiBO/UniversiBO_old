<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
                new Symfony\Bundle\SecurityBundle\SecurityBundle(),
                new Symfony\Bundle\TwigBundle\TwigBundle(),
                new Symfony\Bundle\MonologBundle\MonologBundle(),
                new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
                new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
                new Symfony\Bundle\AsseticBundle\AsseticBundle(),
                new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
                new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
                new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
                new JMS\AopBundle\JMSAopBundle(),
                new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
                new Universibo\Bundle\WebsiteBundle\UniversiboWebsiteBundle(),
                new Universibo\Bundle\LegacyBundle\UniversiboLegacyBundle(),
                new Universibo\Bundle\ForumBundle\UniversiboForumBundle(),
                new Universibo\Bundle\SSOBundle\UniversiboSSOBundle(),
                new Universibo\Bundle\CoreBundle\UniversiboCoreBundle(),
                new Universibo\Bundle\ShibbolethBundle\UniversiboShibbolethBundle(),
                new FOS\UserBundle\FOSUserBundle(),
                new Ornicar\ApcBundle\OrnicarApcBundle()
                );
       

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader
                ->load(
                        __DIR__ . '/config/config_' . $this->getEnvironment()
                                . '.yml');
    }
}
