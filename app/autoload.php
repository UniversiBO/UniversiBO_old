<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

define('PHP_EXTENSION', '.php');
define('UNIVERSIBO_ROOT', realpath(__DIR__.'/..'));

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;
