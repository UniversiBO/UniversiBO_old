<?php

namespace Universibo\Bundle\SSOBundle\DependencyInjection;
use Zend\Uri\Uri;
use Zend\Uri\Exception\InvalidArgumentException;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class UniversiboSSOExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container,
                new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $baseUrl = $this->validateUrl($config, 'base');
        $container->setParameter('universibo_sso.idp_url.info', $this->validateUrl($config, 'info', $baseUrl));
        $container->setParameter('universibo_sso.idp_url.logout', $this->validateUrl($config, 'logout', $baseUrl));
        
        if(!isset($config['route']['after_logout'])) {
            throw new \InvalidArgumentException('universibo_sso.route.after_logout must be set!');
        }
        
        $container->setParameter('universibo_sso.route.after_logout', $config['route']['after_logout']);
    }

    private function validateUrl($config, $name, $base='')
    {
        if (!isset($config['idp_url'][$name])) {
            throw new \InvalidArgumentException(
                    'universibo_sso.idp_url.'.$name.' is not set!');
        }

        try {
            $uri = new Uri($base.$config['idp_url'][$name]);

            return $uri->toString();
        } catch (\Exception $e) {
            throw new \InvalidArgumentException(
                    'universibo_sso.idp_url.'.$name.' is not valid!');
        }
    }
}
