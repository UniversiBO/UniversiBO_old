<?php

/**
 * @author Davide Bellettini
 * @license GPLv2
 */

namespace Universibo\Bundle\LegacyBundle\PearDB;

use Doctrine\DBAL\Driver\Statement;

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

    public function free()
    {
        $this->statement = null;
    }
}
