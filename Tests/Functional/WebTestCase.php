<?php
namespace Universibo\Bundle\ForumBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

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
}
