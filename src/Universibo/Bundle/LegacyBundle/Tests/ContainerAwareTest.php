<?php
namespace Universibo\Bundle\LegacyBundle\Tests;

abstract class ContainerAwareTest extends \PHPUnit_Framework_TestCase
{
    protected static function createKernel($env = 'test', $debug = true)
    {
        require_once __DIR__ .'/../../../../../app/AppKernel.php';

        return new \AppKernel($env, $debug);
    }
}
