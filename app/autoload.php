<?php

use Symfony\Component\ClassLoader\ApcUniversalClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;

define('PHP_EXTENSION', '.php');
define('UNIVERSIBO_ROOT', realpath(__DIR__.'/..'));

if (!$loader = include __DIR__.'/../vendor/autoload.php') {
    $nl = PHP_SAPI === 'cli' ? PHP_EOL : '<br />';
    echo "$nl$nl";
    die('You must set up the project dependencies.'.$nl.
        'Run the following commands in '.dirname(__DIR__).':'.$nl.$nl.
        'curl -s http://getcomposer.org/installer | php'.$nl.
        'php composer.phar install'.$nl);
}

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';
    $loader->add('', __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/Locale/Resources/stubs');
}

$loader->add('',__DIR__.'/../src');
$loader->add('',__DIR__.'/../framework');

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
AnnotationRegistry::registerFile(__DIR__.'/../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');

// Swiftmailer needs a special autoloader to allow
// the lazy loading of the init file (which is expensive)
require_once __DIR__.'/../vendor/swiftmailer/swiftmailer/lib/classes/Swift.php';
Swift::registerAutoload(__DIR__.'/../vendor/swiftmailer/swiftmailer/lib/swift_init.php');
