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
        self::$configDAO = static::getContainer()->get('universibo_forum.dao.config');
    }

    public function testNotFoundReturnsNull()
    {
        $this->assertNull(self::$configDAO->getValue('foobar'));
    }

    public function testSimple()
    {
        $this->assertEquals(1, self::$configDAO->getValue('test'));
    }
}
