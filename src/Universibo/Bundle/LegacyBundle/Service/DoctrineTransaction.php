<?php
namespace Universibo\Bundle\LegacyBundle\Service;

/**
 * Encapsulates transaction management
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
use Universibo\Bundle\LegacyBundle\Entity\DoctrineRepository;

class DoctrineTransaction extends DoctrineRepository
{

    /**
     * (non-PHPdoc)
     * @see Universibo\Bundle\LegacyBundle\Service.TransactionInterface::begin()
     */
    public function begin()
    {
        $this->getConnection()->beginTransaction();
    }

    /**
     * (non-PHPdoc)
     * @see Universibo\Bundle\LegacyBundle\Service.TransactionInterface::commit()
     */
    public function commit()
    {
        $this->getConnection()->commit();
    }

    /**
     * (non-PHPdoc)
     * @see Universibo\Bundle\LegacyBundle\Service.TransactionInterface::rollback()
     */
    public function rollback()
    {
        $this->getConnection()->rollback();
    }

}
