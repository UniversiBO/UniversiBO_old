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

    /**
     * Gets the logged users since academic year beginning
     *
     * @return integer
     */
    public function getLoggedAcademic()
    {
        $query = <<<EOT
SELECT COUNT(*)
   FROM fos_user
   WHERE last_login >= ?
EOT;

        $october1st = new \DateTime('October 1st');
        $now = new \DateTime();

        if ($october1st > $now) {
            $october1st->setDate(date('Y') - 1, 10, 1);
        }

        $stmt = $this
            ->connection
            ->prepare($query)
        ;

        $stmt->bindValue(1, $october1st, 'datetime');
        $stmt->execute();

        return $stmt->fetchColumn();
    }
}
