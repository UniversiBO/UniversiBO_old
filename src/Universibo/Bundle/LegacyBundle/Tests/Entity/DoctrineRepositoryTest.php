<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use Doctrine\DBAL\Connection;

use Universibo\Bundle\LegacyBundle\Tests\ContainerAwareTest;

abstract class DoctrineRepositoryTest extends ContainerAwareTest
{
    /**
     * @var Connection
     */
    protected $db;

    /**
     * @var \AppKernel
     */
    protected static $kernel;

    public static function setUpBeforeClass()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $db = $kernel->getContainer()->get('doctrine.dbal.default_connection');

        $db->executeUpdate(file_get_contents(__DIR__.'/../../../../../../app/sql/pgsql/testdb.sql'));

        $kernel->shutdown();
        restore_error_handler();
    }

    protected function setUp()
    {
        static::$kernel = $kernel = self::createKernel();
        $kernel->boot();

        $this->db = $kernel->getContainer()->get('doctrine.dbal.default_connection');
    }

    protected function tearDown()
    {
        static::$kernel->shutdown();
        restore_error_handler();
    }
}
