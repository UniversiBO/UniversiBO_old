<?php
namespace Universibo\Bundle\ForumBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;
use Symfony\Component\DependencyInjection\Container;

/**
 * Base Functional test case. Inspired (copied) from FOSCommentBundle
 * functional test suites.
 */
class WebTestCase extends BaseWebTestCase
{
    /**
     * gets the kernel class
     *
     * @return string
     */
    protected static function getKernelClass()
    {
        require_once __DIR__.'/app/AppKernel.php';

        return 'Universibo\\Bundle\\ForumBundle\\Tests\\Functional\\AppKernel';
    }

    /**
     * Creates the kernel if needed and gets the container
     *
     * @return Container
     */
    protected static function getContainer()
    {
        if (null === static::$kernel) {
            static::$kernel = static::createKernel();
        }

        static::$kernel->boot();

        return static::$kernel->getContainer();
    }
}
