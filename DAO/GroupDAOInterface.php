<?php
namespace Universibo\Bundle\ForumBundle\DAO;

use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
interface GroupDAOInterface
{
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
