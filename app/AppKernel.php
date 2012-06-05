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
                new Symfony\Bundle\DoctrineBundle\DoctrineBundle(),
                new Symfony\Bundle\AsseticBundle\AsseticBundle(),
                new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
                new FOS\CommentBundle\FOSCommentBundle(),
                new FOS\RestBundle\FOSRestBundle(),
                //new FOS\OAuthServerBundle\FOSOAuthServerBundle(),
                new JMS\SerializerBundle\JMSSerializerBundle($this),
                //new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
                new UniversiBO\Bundle\WebsiteBundle\UniversiBOWebsiteBundle(),
                new UniversiBO\Bundle\LegacyBundle\UniversiBOLegacyBundle(),
                new UniversiBO\Bundle\DidacticsBundle\UniversiBODidacticsBundle(),
            new UniversiBO\Bundle\ForumBundle\UniversiBOForumBundle(),
                new UniversiBO\Bundle\AnswersBundle\UniversiBOAnswersBundle(),
                new Sonata\AdminBundle\SonataAdminBundle(),
            new Universibo\Bundle\SSOBundle\UniversiboSSOBundle(),
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
