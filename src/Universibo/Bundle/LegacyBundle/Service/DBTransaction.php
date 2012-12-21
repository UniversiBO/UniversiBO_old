<?php
namespace Universibo\Bundle\LegacyBundle\Service;

use Doctrine\DBAL\Driver\Connection;
use Universibo\Bundle\LegacyBundle\PearDB\ConnectionWrapper;

/**
 * Encapsulates transaction management
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class DBTransaction implements TransactionInterface
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * @param ConnectionWrapper $db
     */
    public function __construct(ConnectionWrapper $db)
    {
        $this->db = $db->unwrap();
    }

    /**
     * (non-PHPdoc)
     * @see Universibo\Bundle\LegacyBundle\Service.TransactionInterface::begin()
     */
    public function begin()
    {
        $this->db->beginTransaction();
    }

    /**
     * (non-PHPdoc)
     * @see Universibo\Bundle\LegacyBundle\Service.TransactionInterface::commit()
     */
    public function commit()
    {
        if($this->db->isTransactionActive())
            $this->db->commit();
    }

    /**
     * (non-PHPdoc)
     * @see Universibo\Bundle\LegacyBundle\Service.TransactionInterface::rollback()
     */
    public function rollback()
    {
        if($this->db->isTransactionActive())
            $this->db->rollBack();
    }
}
