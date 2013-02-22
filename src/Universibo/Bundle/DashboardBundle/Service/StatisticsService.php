<?php
/**
 * @copyright (c) 2013, Associazione UniversiBO
 * @license GPLv2
 */
namespace Universibo\Bundle\DashboardBundle\Service;

use Doctrine\DBAL\Connection;

/**
 * Statistics service
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class StatisticsService
{
    /**
     * Database connection
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
}
