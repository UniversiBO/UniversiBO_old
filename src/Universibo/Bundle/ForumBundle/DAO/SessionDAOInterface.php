<?php
namespace Universibo\Bundle\ForumBundle\DAO;

use Universibo\Bundle\MainBundle\Entity\User;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
interface SessionDAOInterface
{
    /**
     * Creates a session from the given User
     *
     * @param  User   $user
     * @return string
     */
    public function create($userId, $ip, $userAgent, $id = null);

    /**
     * Deletes a session given the id
     *
     * @param int $id
     */
    public function delete($id);

    /**
     * Tells if a session exists in database
     *
     * @param  int     $id
     * @return boolean
     */
    public function exists($id);
}
