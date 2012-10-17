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
    public function create(User $user, $group);

    /**
     * @param  User $user
     * @return int
     */
    public function findOrCreate(User $user, $group);

    /**
     * @param User $user
     */
    public function update(User $user);
}
