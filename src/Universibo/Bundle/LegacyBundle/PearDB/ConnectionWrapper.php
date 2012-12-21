<?php

/**
 * @author Davide Bellettini
 * @license GPLv2
 */

namespace Universibo\Bundle\LegacyBundle\PearDB;

use Doctrine\DBAL\Connection;

// Ugly hack
class_exists('DB');

/**
 * Pear::DB connection wrapper
 */
class ConnectionWrapper
{
    /**
     * Wrapped connection
     *
     * @var Connection
     */
    private $connection;

    /**
     * Class constructor
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Quotes a value
     *
     * @param  mixed  $input
     * @return string
     */
    public function quote($input = null)
    {
        return $this->connection->quote($input);
    }

    /**
     * __call magic method, to throw exceptions instead of fatal rerror
     * 
     * @param string $methodName
     * @param mixed $args
     * @throws \RuntimeException
     */
    public function __call($methodName, $args)
    {
        throw new \RuntimeException('Method '. $methodName. ' not implemented');
    }

    /**
     * Executes a query
     * 
     * @param string $query
     * @param array $params
     * @return \Universibo\Bundle\LegacyBundle\PearDB\ResultWrapper
     */
    public function query($query, $params = array())
    {
        $stmt = $this->connection->executeQuery($query, $params);

        return new ResultWrapper($stmt);
    }

    public function quoteIdentifier($str)
    {
        return $this->connection->quoteIdentifier($str);
    }
}
