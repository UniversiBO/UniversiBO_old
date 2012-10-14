<?php

namespace Universibo\Bundle\ForumBundle\DAO;

use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class PhpBB3GroupDAO extends AbstractDAO implements GroupDAOInterface
{
    public function addUserToGroup(User $user, $groupId)
    {
    }

    public function removeUserFromGroup(User $user, $groupId)
    {
    }
}
