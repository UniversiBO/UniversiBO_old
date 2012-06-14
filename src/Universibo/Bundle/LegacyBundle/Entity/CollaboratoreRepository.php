<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

use Doctrine\ORM\Query\Expr;

use Doctrine\ORM\EntityManager;

use Doctrine\ORM\EntityRepository;


/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class CollaboratoreRepository extends EntityRepository
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function find($id)
    {
        $result = $this
            ->createQueryBuilder('c')
            ->innerJoin('c.user', 'u', Expr\Join::WITH, 'u.idUtente = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
        
        return count($result) === 0 ? null : $result[0];
    }
    
    public function findAll($shownOnly = false)
    {
        if(!$shownOnly) {
            return $this->__call('findAll', array());
        }
        
        return $this
            ->createQueryBuilder('c')
            ->where('c.show = :show')
            ->setParameter('show', 'Y')
            ->getQuery()
            ->getResult();
    }

    public function insert(Collaboratore $collaboratore)
    {
        ignore_user_abort(1);
        $em = $this->getEntityManager();
        $em->beginTransaction();
        
        $em->persist($collaboratore);
        
        $em->flush();
        $em->commit();
        
        ignore_user_abort(0);
    }
}
