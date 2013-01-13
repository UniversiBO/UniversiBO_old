<?php
/**
 * @copyright (c) 2013, Associazione UniversiBO
 * @license http://opensource.org/licenses/gpl-2.0.php GPLv2
 */

namespace Universibo\Bundle\ForumBundle\Entity;

use Doctrine\DBAL\Driver\Connection;

/**
 * Base class for Doctrine Repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
abstract class DoctrineRepository
{
    /**
     * Database connection
     *
     * @var Connection
     */
    private $connection;

    /**
     * Class constructor
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Connection getter
     *
     * @return Connection
     */
    protected function getConnection()
    {
        return $this->connection;
    }
}
