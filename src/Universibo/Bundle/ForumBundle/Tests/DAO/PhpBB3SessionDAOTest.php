<?php
/**
 * @license GPLv2
 */
namespace Universibo\Bundle\ForumBundle\Tests\DAO;

use Universibo\Bundle\ForumBundle\DAO\PhpBB3SessionDAO;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhpBB3SessionDAOTest extends WebTestCase
{
    /**
     * Session DAO
     *
     * @var PhpBB3SessionDAO
     */
    private $sessionDAO;

    protected function setUp()
    {
        $this->markTestSkipped();

        static::createClient();
        $this->sessionDAO = static::$kernel->getContainer()->get('universibo_forum.dao.session');
    }

    public function testUnexistent()
    {
        $this->assertFalse($this->sessionDAO->exists('foobar'));
    }

    public function testCreateDelete()
    {
        $id = $this->sessionDAO->create(1, '127.0.0.1', 'User agent');

        $this->assertTrue($this->sessionDAO->exists($id));

        $this->sessionDAO->delete($id);

        $this->assertFalse($this->sessionDAO->exists($id));
    }
}
