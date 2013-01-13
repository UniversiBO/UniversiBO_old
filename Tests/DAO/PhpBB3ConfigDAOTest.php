<?php
/**
 * @license GPLv2
 */
namespace Universibo\Bundle\ForumBundle\Tests\DAO;

use Universibo\Bundle\ForumBundle\DAO\PhpBB3ConfigDAO;
use Universibo\Bundle\ForumBundle\Tests\Functional\WebTestCase;

class PhpBB3ConfigDAOTest extends WebTestCase
{
    /**
     *
     * @var PhpBB3ConfigDAO
     */
    private static $configDAO;

    public static function setUpBeforeClass()
    {
        static::$kernel = $kernel = static::createKernel();
        $kernel->boot();

        self::$configDAO = $kernel->getContainer()->get('universibo_forum.dao.config');
    }

    public function testNotFoundReturnsNull()
    {
        $this->assertNull(self::$configDAO->getValue('foobar'));
    }
}
