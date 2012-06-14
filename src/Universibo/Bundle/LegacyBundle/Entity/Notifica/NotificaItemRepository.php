<?php

namespace Universibo\Bundle\LegacyBundle\Entity\Notifica;

use Doctrine\ORM\EntityRepository;

use Doctrine\DBAL\Connection;


/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class NotificaItemRepository extends EntityRepository
{
    /**
     * @param array $ids
     * @return \Universibo\Bundle\LegacyBundle\Entity\Notifica\NotificaItem[] 
     */
    public function findMany(array $ids)
    {
        return $this->findById($ids);
    }

    public function findToSend()
    {
        $query = $this
            ->createQueryBuilder('ni')
            ->where('ni.timestamp <= :timestamp')
            ->andWhere('ni.eliminata = :eliminata')
            ->setParameters(array('timestamp' => time(), 'eliminata' => NotificaItem::NOT_ELIMINATA))
            ->getQuery();
        
        $result = $query->getResult();
        
        return count($result) > 0 ? $result : false;
    }

    public function update(NotificaItem $notification)
    {
        $this->getEntityManager()->merge($notification);
        
        return true;
    }

    public function insert(NotificaItem $notification)
    {
        $this->getEntityManager()->persist($notification);
        
        return true;
    }
}
