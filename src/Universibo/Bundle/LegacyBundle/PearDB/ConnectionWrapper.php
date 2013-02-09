<?php

/**
 * @author Davide Bellettini
 * @license GPLv2
 */

namespace Universibo\Bundle\LegacyBundle\PearDB;

use Doctrine\DBAL\Connection;
use Universibo\Bundle\LegacyBundle\PearDB\ResultWrapper;

/**
 * Pear::DB connection wrapper
 */
class ConnectionWrapper extends AbstractWrapper
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
    public function quote($input = null, $type = null)
    {
        if (null === $input) {
            return 'NULL';
        }

        return $this->connection->quote($input, $type = null);
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

    /**
     * Executes a limit query
     *
     * @param  string        $query
     * @param  integer       $offset
     * @param  integer       $maxResults
     * @return ResultWrapper
     */
    public function limitQuery($query, $offset, $maxResults)
    {
        $query .= ' LIMIT ' . $maxResults . ' OFFSET ' .$offset;

        return $this->query($query);
    }

    /**
     * Identifier quoting
     *
     * @param  string $str
     * @return string
     */
    public function quoteIdentifier($str)
    {
        return $this->connection->quoteIdentifier($str);
    }

    /**
     * Affected rows
     *
     * @return integer
     */
    public function affectedRows()
    {
        return $this->affectedRows;
    }

    /**
     * Fetches one column given a statement
     *
     * @param  string $statement
     * @return mixed
     */
    public function getOne($statement)
    {
        return $this->connection->fetchColumn($statement);
    }

    /**
     * Closes the connection
     *
     * @return void
     */
    public function disconnect()
    {
        $this->connection->close();
    }

    /**
     * Enables/disables autocommit
     *
     * @param boolean $value
     */
    public function autocommit($value)
    {
        if ($value) {
            if ($this->connection->isTransactionActive()) {
                $this->connection->commit();
            }
        } else {
            if (!$this->connection->isTransactionActive()) {
                $this->connection->beginTransaction();
            }
        }
    }

    /**
     * Gets next sequence value PostgreSQL only
     * @param  string $sequence
     * @return type
     */
    public function nextId($sequence)
    {
        $sequence .= '_seq';

        return $this->connection->fetchColumn("SELECT nextval('$sequence')");
    }

    /**
     * Commits the transaction
     */
    public function commit()
    {
        $this->connection->commit();
    }

    /**
     * Rolls the transaction back
     */
    public function rollback()
    {
        $this->connection->rollBack();
    }

    /**
     * Returns underlying connection
     *
     * @return Connection
     */
    public function unwrap()
    {
        return $this->connection;
    }

    /**
     * Executes an update
     *
     * @param string $query
     * @param array  $params
     */
    private function executeUpdate($query, $params = array())
    {
        $this->affectedRows = $this->connection->executeUpdate($query, $params);
    }
}
