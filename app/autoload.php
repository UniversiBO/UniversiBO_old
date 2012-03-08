<?php

require_once __DIR__.'/../vendor/symfony/symfony/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;
$loader = new UniversalClassLoader();

$loader->registerNamespace('Symfony', __DIR__ . '/../vendor/symfony/symfony/src');

$loader->registerNamespaceFallbacks(array(
        __DIR__.'/../src'
));

$loader->registerPrefixFallbacks(array(
        __DIR__.'/../framework',
        __DIR__.'/../universibo/classes',
        __DIR__.'/../universibo/commands',
));

$loader->register();