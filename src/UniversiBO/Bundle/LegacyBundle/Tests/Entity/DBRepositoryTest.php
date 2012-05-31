<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Entity;

use \Error;
use UniversiBO\Bundle\LegacyBundle\App\ErrorHandlers;

abstract class DBRepositoryTest extends \PHPUnit_Framework_TestCase
{
    const TEST_DSN = 'pgsql://universibo:universibo@127.0.0.1/universibo';

    /**
     * @var \DB_pgsql
     */
    protected $db;

    public static function setUpBeforeClass()
    {
        Error::setHandler(ErrorHandlers::LEVEL_CRITICAL, function ($param) {
            throw new \Exception($param['msg']);
        });
    }

    protected function setUp()
    {
        $this->db = \DB::connect(self::TEST_DSN);

        if (\DB::isError($this->db)) {
            $this->markTestSkipped('No DB available');
        }

        $this->db->autocommit(false);
    }

    protected function assertPreConditions()
    {

    }

    protected function tearDown()
    {
        if ($this->db instanceof \DB_common) {
            $this->db->rollback();
            $this->db->disconnect();
        }
    }
}
