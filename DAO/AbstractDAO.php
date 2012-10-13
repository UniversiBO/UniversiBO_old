<?php

namespace Universibo\Bundle\ForumBundle\DAO;

use Doctrine\DBAL\Connection;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPLv2 or later
 */
abstract class AbstractDAO
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @param \Doctrine\DBAL\Connection $connection
     * @param string                    $prefix
     */
    public function __construct(Connection $connection, $prefix = 'phpbb_')
    {
        $this->connection = $connection;
        $this->prefix = $prefix;
    }

    /**
     * @return \Doctrine\DBAL\Connection $connection
     */
    protected function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return string
     */
    protected function getPrefix()
    {
        return $this->prefix;
    }
}
