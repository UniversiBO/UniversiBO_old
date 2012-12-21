<?php

/**
 * @author Davide Bellettini
 * @license GPLv2
 */

namespace Universibo\Bundle\LegacyBundle\PearDB;

use Doctrine\DBAL\Driver\Statement;
use PDO;

/**
 * Pear::DB result wrapper
 */
class ResultWrapper extends AbstractWrapper
{
    /**
     * Wrapped statement
     *
     * @var Statement
     */
    private $statement;

    /**
     * Class construtor
     *
     * @param Statement $statement
     */
    public function __construct(Statement $statement)
    {
        $this->statement = $statement;
    }

    /**
     * Gets underlying statement
     *
     * @return Statement
     */
    public function unwrap()
    {
        return $this->statement;
    }

    /**
     * Gets the affected rows of latest executeUpdate
     *
     * @return integer
     */
    public function numRows()
    {
        return $this->statement->rowCount();
    }

    /**
     * Fetches a row into referenced variable
     *
     * @param  mixed   $row
     * @return boolean
     */
    public function fetchInto(&$row)
    {
        $row = $this->statement->fetch(PDO::FETCH_NUM);

        return $row !== false;
    }

    /**
     * Frees the statement
     */
    public function free()
    {
        $this->statement = null;
    }

    /**
     * Fetches a row
     *
     * @return boolean|array
     */
    public function fetchRow()
    {
        return $this->statement->fetch(PDO::FETCH_NUM);
    }

    public function autocommit($value)
    {
        if ($value) {

        }
    }
}
