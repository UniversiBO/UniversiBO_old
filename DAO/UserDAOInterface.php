<?php
namespace Universibo\Bundle\ForumBundle\DAO;

use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
interface UserDAOInterface
{
    /**
     * @param  User $user
     * @return int
     */
    public function find(User $user);

    /**
     * @param User $user
     */
    public function create(User $user);

    /**
     * @param  User $user
     * @return int
     */
    public function findOrCreate(User $user);

    /**
     * @param User $user
     */
    public function update(User $user);
    
    /**
     * @param User $user
     * @param int  $groupId
     */
    public function addUserToGroup(User $user, $groupId);

    /**
     * @param User $user
     * @param int  $groupId
     */
    public function removeUserFromGroup(User $user, $groupId);
}
