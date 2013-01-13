<?php
/**
 * @license GPLv2
 */
namespace Universibo\Bundle\ForumBundle\Tests\DAO;

use Universibo\Bundle\ForumBundle\DAO\ForumDAOInterface;
use Universibo\Bundle\ForumBundle\DAO\PhpBB3ForumDAO;
use Universibo\Bundle\ForumBundle\Tests\Functional\WebTestCase;

class PhpBB3ForumDAOTest extends WebTestCase
{
    /**
     *
     * @var PhpBB3ForumDAO
     */
    private static $forumDAO;

    public static function setUpBeforeClass()
    {
        self::$forumDAO = static::getContainer()->get('universibo_forum.dao.forum');
    }

    public function testCreate()
    {
        $title = 'Test title';
        $description = 'Test description';
        $type = ForumDAOInterface::TYPE_FORUM;

        $this->assertGreaterThan(0, $forumId = self::$forumDAO->create($title, $description, $type));

        $this->assertEquals($title, self::$forumDAO->getForumName($forumId));
    }

    public function testMaxId()
    {
        $this->assertGreaterThan(0, $forumId = self::$forumDAO->getMaxId());
    }

    public function testRename()
    {
        $forumDAO = self::$forumDAO;

        $forumId = $forumDAO->getMaxId();

        $newName = 'New name';
        $forumDAO->rename($forumId, $newName);

        $this->assertEquals($newName, self::$forumDAO->getForumName($forumId));
    }
}
