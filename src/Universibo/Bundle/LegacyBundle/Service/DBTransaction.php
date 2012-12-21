<?php
namespace Universibo\Bundle\LegacyBundle\Service;

/**
 * Encapsulates transaction management
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class DBTransaction implements TransactionInterface
{

    /**
     * @var \Universibo\Bundle\LegacyBundle\PearDB\ConnectionWrapper
     */
    private $db;

    /**
     * @param \Universibo\Bundle\LegacyBundle\PearDB\ConnectionWrapper $db
     */
    public function __construct(\Universibo\Bundle\LegacyBundle\PearDB\ConnectionWrapper $db)
    {
        $this->db = $db;
    }

    /**
     * (non-PHPdoc)
     * @see Universibo\Bundle\LegacyBundle\Service.TransactionInterface::begin()
     */
    public function begin()
    {
        if (($result = $this->db->autoCommit(false)) instanceof \DB_error) {
            throw new TransactionException($result->__toString());
        }
    }

    /**
     * (non-PHPdoc)
     * @see Universibo\Bundle\LegacyBundle\Service.TransactionInterface::commit()
     */
    public function commit()
    {
        if (($result = $this->db->autoCommit(true)) instanceof \DB_error) {
            throw new Exception($result->__toString());
        }
    }

    /**
     * (non-PHPdoc)
     * @see Universibo\Bundle\LegacyBundle\Service.TransactionInterface::rollback()
     */
    public function rollback()
    {
        if (($result = $this->db->rollback()) instanceof \DB_error) {
            throw new TransactionException($result->__toString());
        }
    }

}
