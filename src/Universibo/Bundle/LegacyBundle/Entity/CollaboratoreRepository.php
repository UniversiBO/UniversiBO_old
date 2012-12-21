<?php
/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPLv2
 * @copyright (c) 2012, Associazione UniversiBO
 */
namespace Universibo\Bundle\LegacyBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Collaboratore repository
 */
class CollaboratoreRepository extends EntityRepository
{
    /**
     * Inserts a collaborator
     *
     * @param Collaboratore $collaborator
     */
    public function insert(Collaboratore $collaborator)
    {
        $em = $this->getEntityManager();
        $em->persist($collaborator);
        $em->flush($collaborator);
    }
}
