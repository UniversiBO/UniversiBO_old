<?php

namespace Universibo\Bundle\LegacyBundle\Service;

/**
 * Interface for transaction handlers
 * 
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
interface TransactionInterface
{
    /**
     * begins the transaction
     * @throws TransactionException
     */
    public function begin();
    
    /**
     * commits the transaction
     * @throws TransactionException
     */
    public function commit();
    
    /**
     * rolls back the transaction
     * @throws TransactionException
     */
    public function rollback();
}