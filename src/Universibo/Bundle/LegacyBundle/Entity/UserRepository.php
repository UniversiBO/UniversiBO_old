<?php

namespace Universibo\Bundle\LegacyBundle\Entity;

use Doctrine\ORM\AbstractQuery;

use Doctrine\DBAL\Connection;

use Doctrine\ORM\EntityRepository;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
/**
 * User repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class UserRepository extends EntityRepository implements UserProviderInterface
{
    public function findByUsername($username, $caseSensitive = false)
    {
        return $this->findOneByUsername($username, $caseSensitive);
    }

    public function findOneByUsername($username, $caseSensitive = false)
    {
        $condition = $caseSensitive ? 'u.username = :username' : 'LOWER(u.username) = LOWER(:username)';
        $query = $this
            ->createQueryBuilder('u')
            ->where($condition)
            ->setParameter('username', $username)
            ->getQuery();

        $result = $query->getResult();

        return count($result) === 0 ? false : $result[0];
    }

    /**
     * Tells if a username exists
     * @param  string  $username
     * @return boolean
     */
    public function usernameExists($username, $caseSensitive = false)
    {
        return $this->findOneByUsername($username, $caseSensitive) instanceof User;
    }

    /**
     * Tells if an active directory username (email) exists
     *
     * @param  string  $adUsername
     * @return boolean
     */
    public function activeDirectoryUsernameExists($adUsername)
    {
        return $this->findOneByADLogin($adUsername) instanceof User;
    }

    /**
     * @param  int    $id
     * @return string
     */
    public function getUsernameFromId($id)
    {
        $user = $this->find($id);

        return $user instanceof User ? $user->getUsername() : null;
    }

    public function getIdFromADUsername($adUsername)
    {
        $user = $this->findOneByADUsername($adUsername);

        return $user instanceof User ? $user->getADUsername() : null;
    }

    /**
     * Insert a user
     *
     * @param  User    $user
     * @return boolean
     */
    public function insertUser(User $user)
    {
        ignore_user_abort(1);

        $em = $this->getEntityManager();
        $em->beginTransaction();
        $em->persist($user);
        $em->flush();
        $em->commit();

        ignore_user_abort(0);

        return $return;
    }

    public function updateUser(User $user)
    {
        $this->getEntityManager()->merge($user);
        $this->getEntityManager()->flush();

        return true;
    }

    public function updateEmail(User $user)
    {
        return $this->updateUser($user);
    }

    public function updateUltimoLogin(User $user)
    {
        return $this->updateUser($user);
    }

    public function updateADUsername(User $user)
    {
        return $this->updateUser($user);
    }

    public function updatePassword(User $user)
    {
        return $this->updateUser($user);
    }

    public function findCollaboratori()
    {
        return $this
            ->createQueryBuilder('u')
            ->where('u.groups > 2')
            ->andWhere('u.groups <> 8')
            ->andWhere('u.groups <> 16')
            ->andWhere('u.groups <> 32')
            ->andWhere('u.eliminato = :sospeso')
            ->orderBy('u.username', 'ASC')
            ->setParameter('sospeso', User::NOT_ELIMINATO)
            ->getQuery()
            ->getResult();
    }

    public function getIdUsersFromDesiredGroups(
            array $arrayWithDesiredGroupsConstant)
    {
        if (count($arrayWithDesiredGroupsConstant) === 0)

            return array();

        $result = $this
            ->createQueryBuilder('u')
            ->where('u.groups IN :groups')
            ->setParameter('groups', $arrayWithDesiredGroupsConstant, array(Connection::PARAM_INT_ARRAY))
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);

        $ret = array();

        foreach ($result as $row) {
            $ret[$user->getGroups()][] = $user->getIdUSer();
        }

        return $ret;
    }

    public function updateGroups(User $user)
    {
        $this->updateUser($user);

        return true;
    }

    public function findLike($username = '%', $email = '%')
    {
        return $this
            ->createQueryBuilder('u')
            ->where('u.username LIKE :username')
            ->andWhere('u.email LIKE :email')
            ->setParameter('username', $username)
            ->setParameter('email', $email)
            ->getQuery()
            ->execute();
    }

    public function loadUserByUsername($username)
    {
        return $this->findByUsername($username);
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function delete(User $user)
    {
        $user->setEliminato(true);

        return $this->updateUser($user);
    }

    public function supportsClass($class)
    {
        return 'UniversiBO\\Bundle\\LegacyBundle\\Entity\\User' === $class;
    }
}
