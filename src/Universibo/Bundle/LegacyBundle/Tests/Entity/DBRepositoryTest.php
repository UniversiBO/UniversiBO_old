<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use AppKernel;
use Exception;
use Universibo\Bundle\LegacyBundle\App\ErrorHandlers;
use Universibo\Bundle\LegacyBundle\Framework\Error;
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
    }

    protected function setUp()
    {
        static::$kernel = $kernel = self::createKernel();
        $kernel->boot();

        $this->db = $kernel->getContainer()->get('universibo_legacy.db.connection.main');

        if (!$this->db->unwrap()->isTransactionActive()) {
            $this->db->unwrap()->beginTransaction();
        }
    }

    protected function assertPreConditions()
    {

    }

    protected function tearDown()
    {
        $db = $this->db instanceof ConnectionWrapper ? $this->db->unwrap() : $this->db;

        if ($db->isTransactionActive()) {
            $db->rollback();
        }

        static::$kernel->shutdown();
        restore_error_handler();
    }
}
