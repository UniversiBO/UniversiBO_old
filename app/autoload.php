<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\ClassLoader\ApcClassLoader;

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

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
AnnotationRegistry::registerFile(__DIR__.'/../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');

$loader = new ApcClassLoader('sf2', $loader);
$loader->register(true);
