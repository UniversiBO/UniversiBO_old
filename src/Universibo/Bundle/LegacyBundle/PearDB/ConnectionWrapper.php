<?php

/**
 * @author Davide Bellettini
 * @license GPLv2
 */

namespace Universibo\Bundle\LegacyBundle\PearDB;

use Doctrine\DBAL\Connection;
use RuntimeException;
use Universibo\Bundle\LegacyBundle\PearDB\ResultWrapper;

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
     * Affected rows
     *
     * @var integer
     */
    private $affectedRows = 0;

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
     * @param  string           $methodName
     * @param  mixed            $args
     * @throws RuntimeException
     */
    public function __call($methodName, $args)
    {
        throw new RuntimeException('Method '. $methodName. ' not implemented');
    }

    /**
     * Executes a query
     *
     * @param  string        $query
     * @param  array         $params
     * @return ResultWrapper
     */
    public function query($query, $params = array())
    {
        if (preg_match('/UPDATE|DELETE|INSERT/', $query)) {
            $this->executeUpdate($query, $params);

            return;
        }

        $stmt = $this->connection->executeQuery($query, $params);

        return new ResultWrapper($stmt);
    }

    public function limitQuery($query, $offset, $maxResults)
    {
        $query .= ' LIMIT ' . $maxResults . ' OFFSET ' .$offset;

        return $this->query($query);
    }

    public function quoteIdentifier($str)
    {
        return $this->connection->quoteIdentifier($str);
    }

    public function affectedRows()
    {
        return $this->affectedRows;
    }

    public function getOne($statement)
    {
        return $this->connection->fetchColumn($statement);
    }

    public function disconnect()
    {
        return $this->connection->close();
    }

    private function executeUpdate($query, $params = array())
    {
        $this->affectedRows = $this->connection->executeUpdate($query, $params);
    }
}
