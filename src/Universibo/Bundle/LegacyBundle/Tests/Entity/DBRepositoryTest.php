<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use \Error;
use Universibo\Bundle\LegacyBundle\App\ErrorHandlers;

abstract class DBRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \DB_pgsql
     */
    protected $db;
    
    /**
     * @var \AppKernel
     */
    protected static $kernel;
    
    protected static function createKernel()
    {
        require_once __DIR__ .'/../../../../../../app/AppKernel.php';
        return new \AppKernel('test', true);
    }

    public static function setUpBeforeClass()
    {
        Error::setHandler(ErrorHandlers::LEVEL_CRITICAL, function ($param) {
            throw new \Exception($param['msg']);
        });
        
        $kernel = self::createKernel();
        $kernel->boot();
        $db = $kernel->getContainer()->get('universibo_legacy.db.connection.main');
        
        $db->query(file_get_contents(__DIR__.'/../../../../../../dbms/pgsql/testdb.sql'));
        
        $kernel->shutdown();
    }

    protected function setUp()
    {
        static::$kernel = $kernel = self::createKernel();
        $kernel->boot();
        
        $this->db = $kernel->getContainer()->get('universibo_legacy.db.connection.main');

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
        
        static::$kernel->shutdown();
    }
}
