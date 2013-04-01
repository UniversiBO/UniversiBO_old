<?php

namespace Universibo\Bundle\Corebundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * BatchRenameRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
    /**
     * Converts id into username
     *
     * @param  integer $id
     * @return string
     */
    public function getUsernameFromId($id)
    {
        $user = $this->find($id);

        return $user instanceof User ? $user->getUsername() : null;
    }

    /**
     * Tells if a username exists
     *
     * @param  string  $username
     * @return boolean
     */
    public function usernameExists($username)
    {
        return $this->findOneByUsername($username) instanceof User;
    }

    /**
     * User search feature
     *
     * @param  string  $usernameQuery
     * @param  string  $mailQuery
     * @param  boolean $showLocked
     * @param  boolean $showDisabled
     * @return User[]
     */
    public function search($usernameQuery, $mailQuery, $showLocked = false,
            $showDisabled = false)
    {
        $qb = $this->createQueryBuilder('u');

        if (!$showLocked) {
            $qb->andWhere('u.locked = false');
        }

        if (!$showDisabled) {
            $qb->andWhere('u.enabled = true');
        }

        if (strlen($usernameQuery) > 0) {
            $qb->andWhere('u.usernameCanonical LIKE ?0');
        }

        if (strlen($mailQuery) > 0) {
            $qb->andWhere('u.emailCanonical LIKE ?1');
        }

        return
            $qb -> getQuery()
                -> setParameters(array(mb_strtolower($usernameQuery), mb_strtolower($mailQuery)))
                -> getResult();
    }

    /**
     * Counts how many users belong to a person
     *
     * @param  Person $person
     * @return type
     */
    public function countByPerson(Person $person)
    {
        $dql = <<<EOT
SELECT COUNT(u)
    FROM UniversiboCoreBundle:User u
    WHERE
            u.person  = ?0
        AND u.locked  = false
        AND u.enabled = true
EOT;

        $query = $this
            ->getEntityManager()
            ->createQuery($dql)
        ;

        $query->execute(array($person));

        return $query->getSingleScalarResult();
    }

    /**
     * Finds all the collaborators
     *
     * @return User[]
     */
    public function findCollaborators()
    {
        $dql = <<<EOT
SELECT u, c
    FROM
        UniversiboCoreBundle:User u
    LEFT JOIN u.contacts c
    WHERE
            u.legacyGroups IN (:groups)
        AND u.locked = false
        AND u.enabled = true
EOT;
        $query = $this
            ->getEntityManager()
            ->createQuery($dql)
            ->setParameter('groups', array(4, 64))
        ;

        return $query->getResult();
    }

    /**
     * @param  Person                   $person
     * @throws NonUniqueResultException
     * @return User
     */
    public function findOneAllowedToLogin(Person $person)
    {
        $dql = <<<EOT
SELECT u
    FROM UniversiboCoreBundle:User u
    WHERE
            u.person = ?0
        AND u.locked = false
        AND u.enabled = true
EOT;

        $query = $this
            ->getEntityManager()
            ->createQuery($dql)
            ->setParameter(0, $person)
        ;

        return $query->getSingleResult();
    }

    /**
     * Merges an user and flushes the entity manager
     *
     * @param  User $user
     * @return User
     */
    public function save(User $user)
    {
        $em = $this->getEntityManager();
        $user = $em->merge($user);
        $em->flush($user);

        return $user;
    }

    /**
     * Counts active and not locked users
     *
     * @return integer
     */
    public function countActive()
    {
        $result = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('COUNT(u)')
            ->from('UniversiboCoreBundle:User', 'u')
            ->andWhere('u.enabled = true')
            ->andWhere('u.locked = false')
            ->getQuery()
            ->getScalarResult()
        ;

        return $result[0][1];
    }
}
