<?php

namespace Universibo\Bundle\ForumBundle\DAO;

use Doctrine\DBAL\Connection;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\ForumBundle\DAO\UserDAOInterface;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class PhpBB3GroupDAO extends AbstractDAO implements GroupDAOInterface
{
    /**
     * @var UserDAOInterface
     */
    private $userDAO;

    /**
     *
     * @param Connection       $connection
     * @param string           $prefix
     * @param UserDAOInterface $userDAO
     */
    public function __construct(Connection $connection, $prefix = 'phpbb_',
            UserDAOInterface $userDAO) {
        parent::__construct($connection, $prefix);

        $this->userDAO = $userDAO;
    }

    public function addUserToGroup(User $user, $groupId)
    {
        if (!$this->exists($groupId)) {
            return false;
        }

        $userId = $this->userDAO->findOrCreate($user);
        if ($this->inGroup($userId, $groupId)) {
            return false;
        }

        $query = <<<EOT
INSERT INTO {$this->getPrefix()}user_group
    (group_id, user_id, user_pending)
    VALUES
    (?,?,0)
EOT;

        return $this->getConnection()->executeUpdate($query, array($groupId, $userId)) > 0;
    }

    public function removeUserFromGroup(User $user, $groupId)
    {
        if (!$this->exists($groupId)) {
            return false;
        }

        $userId = $this->userDAO->findOrCreate($user);
        if (!$this->inGroup($userId, $groupId)) {
            return false;
        }

        $query = <<<EOT
DELETE FROM {$this->getPrefix()}user_group
    WHERE
            group_id = ?
        AND user_id = ?
EOT;

        return $this->getConnection()->executeUpdate($query, array($groupId, $userId)) > 0;
    }

    private function inGroup($userId, $groupId)
    {
        $query = <<<EOT
SELECT COUNT(*)
    FROM {$this->getPrefix()}user_group
    WHERE
            group_id = ?
        AND user_id = ?
EOT;
        $result = $this->getConnection()->executeQuery($query, array($groupId, $userId));

        return $result->fetchColumn() > 0;
    }

    private function exists($groupId)
    {
        $query = <<<EOT
SELECT COUNT(*)
    FROM {$this->getPrefix()}groups
    WHERE
        group_id = ?
EOT;
        $result = $this->getConnection()->executeQuery($query, array($groupId, $userId));

        return $result->fetchColumn() > 0;
    }
}
