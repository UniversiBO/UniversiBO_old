<?php
/**
 * @license GPLv2
 */
namespace Universibo\Bundle\ForumBundle\Tests\DAO;

use Universibo\Bundle\ForumBundle\DAO\PhpBB3SessionDAO;
use Universibo\Bundle\ForumBundle\Tests\Functional\WebTestCase;

class PhpBB3SessionDAOTest extends WebTestCase
{
    /**
     * Session DAO
     *
     * @var PhpBB3SessionDAO
     */
    private static $sessionDAO;

    public static function setUpBeforeClass()
    {
        self::$sessionDAO = static::getContainer()->get('universibo_forum.dao.session');
    }

    public function testUnexistent()
    {
        $this->assertFalse(self::$sessionDAO->exists('foobar'));
    }

    public function testCreateDelete()
    {
        $id = self::$sessionDAO->create(1, '127.0.0.1', 'User agent');

        $this->assertTrue(self::$sessionDAO->exists($id));

        self::$sessionDAO->delete($id);

        $this->assertFalse(self::$sessionDAO->exists($id));
    }
}
