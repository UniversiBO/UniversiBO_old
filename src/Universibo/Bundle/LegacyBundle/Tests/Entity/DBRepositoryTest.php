<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use AppKernel;
use Universibo\Bundle\LegacyBundle\Framework\Error;
use Exception;
use Universibo\Bundle\LegacyBundle\App\ErrorHandlers;
use Universibo\Bundle\LegacyBundle\PearDB\ConnectionWrapper;
use Universibo\Bundle\LegacyBundle\Tests\ContainerAwareTest;

abstract class DBRepositoryTest extends ContainerAwareTest
{
    /**
     * @var ConnectionWrapper
     */
    protected $db;

    /**
     * @var AppKernel
     */
    protected static $kernel;

    public static function setUpBeforeClass()
    {
        Error::setHandler(ErrorHandlers::LEVEL_CRITICAL, function ($param) {
            throw new Exception($param['msg']);
        });

        $kernel = self::createKernel();
        $kernel->boot();
        $db = $kernel->getContainer()->get('universibo_legacy.db.connection.main');

        $db->query(file_get_contents(__DIR__.'/../../../../../../app/sql/pgsql/testdb.sql'));

        $kernel->shutdown();
        restore_error_handler();
    }

    protected function setUp()
    {
        static::$kernel = $kernel = self::createKernel();
        $kernel->boot();

        $this->db = $kernel->getContainer()->get('universibo_legacy.db.connection.main');
        $this->db->autocommit(false);
    }

    protected function assertPreConditions()
    {

    }

    protected function tearDown()
    {
        if ($this->db instanceof ConnectionWrapper) {
            $this->db->rollback();
            $this->db->disconnect();
        }

        static::$kernel->shutdown();
        restore_error_handler();
    }
}
