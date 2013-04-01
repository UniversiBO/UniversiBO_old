<?php
/**
 * @license GPLv2
 */
namespace Universibo\Bundle\ForumBundle\Tests\DAO;

use Universibo\Bundle\ForumBundle\DAO\PhpBB3ConfigDAO;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhpBB3ConfigDAOTest extends WebTestCase
{
    /**
     *
     * @var PhpBB3ConfigDAO
     */
    private $configDAO;

    protected function setUp()
    {
        $this->markTestSkipped();

        static::createClient();
        $this->configDAO = static::$kernel->getContainer()->get('universibo_forum.dao.config');
    }

    public function testNotFoundReturnsNull()
    {
        $this->assertNull($this->configDAO->getValue('foobar'));
    }

    public function testSimple()
    {
        $this->assertEquals(1, $this->configDAO->getValue('test'));
    }
}
