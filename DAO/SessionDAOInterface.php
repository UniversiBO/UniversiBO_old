<?php
namespace Universibo\Bundle\ForumBundle\DAO;

use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
interface SessionDAOInterface
{
    /**
     * Creates a session from the given User
     * @param User $user
     */
    public function create($userId, $ip, $userAgent);

    /**
     * Deletes a session given the id
     * @param int $id
     */
    public function delete($id);
}
