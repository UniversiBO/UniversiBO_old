<?php

namespace Universibo\Bundle\LegacyBundle\Entity;

use Doctrine\DBAL\Connection;

abstract class DoctrineRepository
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function getConnection()
    {
        return $this->connection;
    }
}