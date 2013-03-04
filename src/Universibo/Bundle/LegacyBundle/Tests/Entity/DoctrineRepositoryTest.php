<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

use Doctrine\DBAL\Connection;

abstract class DoctrineRepositoryTest extends DBRepositoryTest
{
    /**
     * @var Connection
     */
    protected $db;

    /**
     * @var \AppKernel
     */
    protected static $kernel;

    protected function setUp()
    {
        parent::setUp();

        $this->db = $this->db->unwrap();
    }
}
