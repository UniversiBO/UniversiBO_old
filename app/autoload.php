<?php
define('PHP_EXTENSION', '.php');
define('UNIVERSIBO_ROOT', realpath(__DIR__.'/..'));

use Symfony\Component\ClassLoader\UniversalClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
        'Symfony\\Bundle\\AsseticBundle'       => __DIR__.'/../vendor/symfony/assetic-bundle',
        'Symfony'                              => __DIR__.'/../vendor/symfony/symfony/src',
        'Sensio\\Bundle\\FrameworkExtraBundle' => __DIR__.'/../vendor/sensio/framework-extra-bundle',
        'Sensio\\Bundle\\GeneratorBundle'      => __DIR__.'/../vendor/sensio/generator-bundle',
        'CG'                                   => __DIR__.'/../vendor/jms/cg/src',
        'JMS\\AopBundle'                       => __DIR__.'/../vendor/jms/aop-bundle',
        'JMS\\SecurityExtraBundle'             => __DIR__.'/../vendor/jms/security-extra-bundle',
        'Doctrine\\Common'                     => __DIR__.'/../vendor/doctrine/common/lib',
        'Doctrine\\DBAL\\Migrations'           => __DIR__.'/../vendor/doctrine/migrations/lib',
        'Doctrine\\DBAL'                       => __DIR__.'/../vendor/doctrine/dbal/lib',
        'Doctrine\\ORM'                        => __DIR__.'/../vendor/doctrine/orm/lib',
        'Monolog'                              => __DIR__.'/../vendor/monolog/monolog/src',
        'Assetic'                              => __DIR__.'/../vendor/kriswallsmith/assetic/src',
        'Metadata'                             => __DIR__.'/../vendor/jms/metadata/src',
        'Zend'                                 => __DIR__.'/../vendor/ecolinet/zf2/library',
));
$loader->registerPrefixes(array(
//        'Twig_Extensions_' => __DIR__.'/../vendor/twig-extensions/lib',
        'Twig_'            => __DIR__.'/../vendor/twig/twig/lib',
));

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->registerPrefixFallbacks(array(
            __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs',
    ));
}

$loader->registerPrefixFallbacks(array(
        __DIR__.'/../framework',
));

$loader->registerNamespaceFallbacks(array(
        __DIR__.'/../src',
));
$loader->register();

AnnotationRegistry::registerLoader(function($class) use ($loader) {
    $loader->loadClass($class);
    return class_exists($class, false);
});
AnnotationRegistry::registerFile(__DIR__.'/../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');

// Swiftmailer needs a special autoloader to allow
// the lazy loading of the init file (which is expensive)
require_once __DIR__.'/../vendor/swiftmailer/swiftmailer/lib/classes/Swift.php';
Swift::registerAutoload(__DIR__.'/../vendor/swiftmailer/lib/swift_init.php');