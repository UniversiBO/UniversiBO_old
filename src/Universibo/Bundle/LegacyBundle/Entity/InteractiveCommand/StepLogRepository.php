<?php
namespace Universibo\Bundle\LegacyBundle\Entity\InteractiveCommand;

use Doctrine\ORM\EntityRepository;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class StepLogRepository extends EntityRepository
{
    /**
     * @param unknown_type $userId
     * @param unknown_type $className
     *
     * @return null|StepLog
     */
    public function findLatestPositive($userId, $className)
    {
        $result = $this->findPositive($userId, $className);

        return count($result) === 0 ? null : $result[0];
    }

    /**
     * @param  int      $userId
     * @param  string   $className
     * @param  int      $limit
     * @return Ambigous <\Doctrine\ORM\mixed, unknown, boolean>
     */
    public function findPositive($userId, $className = null, $limit = null)
    {
        $params = array(
                'idUtente' => $userId,
                'esitoPositivo' => 'S'
        );

        $builder = $this
            ->createQueryBuilder('sl')
            ->andWhere('sl.idUtente = :idUtente');

        if ($className !== null) {
            $builder->andWhere('sl.nomeClasse = :nomeClasse');
            $params['nomeClasse'] = $className;
        }

        $builder
            ->andWhere('sl.esitoPositivo = :esitoPositivo')
            ->addOrderBy('sl.dataUltimaInterazione', 'DESC');

        if (is_int($limit)) {
            $query->setMaxResults($limit);
        }

        $query = $builder->getQuery();

        return $query->execute($params);
    }

    public function insert(StepLog $stepLog)
    {
        $result = $this->_em->persist($stepLog);
        $this->_em->flush();

        return $result;
    }
}
