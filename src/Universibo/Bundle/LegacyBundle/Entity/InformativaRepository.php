<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * @todo Informativa Entity
 */
class InformativaRepository extends EntityRepository
{
    /**
     * @param  int         $time
     * @return Informativa
     */
    public function findByTime($time)
    {
        $qb = $this->createQueryBuilder('i');

        return $qb
             ->andWhere('i.dataPubblicazione <= :time')
             ->andWhere('i.dataFine IS NULL OR i.dataFine > :time')
             ->orderBy('i.id', 'DESC')
             ->setMaxResults(1)
             ->setParameter('time', $time)
             ->getQuery()
             ->getOneOrNullResult();
    }
}
