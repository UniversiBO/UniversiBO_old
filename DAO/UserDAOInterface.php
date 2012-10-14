<?php
namespace Universibo\Bundle\ForumBundle\DAO;

use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
interface UserDAOInterface
{
    /**
     * @param  User    $user
     * @return boolean
     */
    public function exists(User $user);

    /**
     * @param User $user
     */
    public function create(User $user);

    /**
     * @param User $user
     */
    public function update(User $user);
}
