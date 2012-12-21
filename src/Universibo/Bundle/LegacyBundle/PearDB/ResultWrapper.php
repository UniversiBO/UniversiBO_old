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
class ResultWrapper
{
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

    public function unwrap()
    {
        return $this->statement;
    }

    public function numRows()
    {
        return $this->statement->rowCount();
    }

    public function fetchInto(&$row)
    {
        $row = $this->statement->fetch(PDO::FETCH_NUM);

        return $row !== false;
    }

    public function free()
    {
        $this->statement = null;
    }

    public function fetchRow()
    {
        return $this->statement->fetch(PDO::FETCH_NUM);
    }
}
