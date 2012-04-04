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
    }
    
    protected function assertPreConditions()
    {
        self::assertInstanceOf('DB_common', $this->db);
    }
    
    protected function tearDown()
    {
        $this->db->disconnect();
    }
}