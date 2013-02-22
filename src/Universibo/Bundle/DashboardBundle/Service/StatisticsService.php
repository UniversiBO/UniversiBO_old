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

    /**
     * Gets logged from week
     *
     * @return integer
     */
    public function getLoggedUsersWeek()
    {
        return $this
            ->connection
            ->fetchColumn('SELECT * FROM loggati_168h_count')
        ;
    }

    /**
     * Gets users logged in latest 24 hours
     *
     * @return integer
     */
    public function getLoggedUsers24h()
    {
        return $this
            ->connection
            ->fetchColumn('SELECT * FROM loggati_24h_count')
        ;
    }
}
