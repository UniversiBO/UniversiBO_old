<?php

namespace Universibo\Bundle\ForumBundle\DAO;

use Doctrine\DBAL\Connection;
use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class PhpBB3UserDAO extends AbstractDAO implements UserDAOInterface
{
    /**
     *
     * @var array
     */
    // be careful when adding groups
    // these must be from higher level to lower
    private static $translations = array (
        'ROLE_ADMIN' => 10024,
        'ROLE_MODERATOR' => 6,
    );

    public function create(User $user)
    {
        $query = <<<EOT
INSERT into {$this->getPrefix()}users
(
    username,
    username_clean,
    user_email,
    user_regdate,
    group_id
)
VALUES
    (?, ?, ?, ?, ?)
EOT;

        $groupId = $this->getGroup($user);

        $this->getConnection()->executeUpdate($query, array(
            $user->getUsername(),
            $user->getUsernameCanonical(),
            $user->getEmail(),
            time(),
            $groupId
        ));

        $id = $this->getConnection()->lastInsertId('phpbb_users_seq');

        if ($groupId > 2) {
            $this->addToGroup($id, $groupId);
        }

        $newestQuery = <<<EOT
UPDATE {$this->getPrefix()}config
    SET
        config_value = ?
    WHERE
        config_name = 'newest_user_id'
EOT;

        $this
            ->getConnection()
            ->executeUpdate($newestQuery, array($id))
        ;

        $newestQuery2 = <<<EOT
UPDATE {$this->getPrefix()}config
    SET
        config_value = ?
    WHERE
        config_name = 'newest_username'
EOT;

        $this
            ->getConnection()
            ->executeUpdate($newestQuery2, array($user->getUsername()))
        ;

        return $id;
    }

    public function find(User $user)
    {
        $found = $this->findWithGroup($user);

        return is_array($found) ? $found['user_id'] : $found;
    }

    public function findOrCreate(User $user)
    {
        $data = $this->findWithGroup($user);

        if (is_array($data)) {
            if ($this->getGroup($user) !== $data['group_id']) {
                $this->update($user);
            }

            return $data['user_id'];
        }

        return $this->create($user);
    }

    public function update(User $user)
    {
        $query = <<<EOT
UPDATE {$this->getPrefix()}users
    SET user_email = ?,
        group_id = ?
    WHERE username = ?
EOT;

        $groupId = $this->getGroup($user);

        $userId = $user->getId();
        if ($groupId > 2) {
            $this->addToGroup($userId, $groupId);
        }

        $roles = array_flip(self::$translations);

        foreach ($this->getRemovableGroups($userId) as $removableId) {
            if (!$user->hasRole($roles[$removableId])) {
                $this->removeUserFromGroup($user, $removableId);
            }
        }

        return $this->getConnection()->executeUpdate($query,
                array($user->getEmail(),$groupId,
                    $user->getUsername())) > 0;
    }

    public function addUserToGroup(User $user, $groupId)
    {
        if (!$this->groupExists($groupId)) {
            return false;
        }

        $userId = $this->findOrCreate($user);

        return $this->addToGroup($userId, $groupId);
    }

    public function removeUserFromGroup(User $user, $groupId)
    {
        if (!$this->groupExists($groupId)) {
            return false;
        }

        $userId = $this->findOrCreate($user);
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

    private function getRemovableGroups($userId)
    {
        $query = <<<EOT
SELECT group_id
    FROM {$this->getPrefix()}user_group
    WHERE
            group_id IN (?)
        AND user_id = ?
EOT;

        $groupIds = array_values(self::$translations);
        $result = $this->getConnection()->executeQuery($query, array(
            $groupIds,
            $userId
        ), array(
            Connection::PARAM_INT_ARRAY,
            null,
        ));

        $removable = array();

        while (false !== ($value = $result->fetchColumn())) {
            $removable[] = $value;
        }

        return $removable;
    }

    private function groupExists($groupId)
    {
        $query = <<<EOT
SELECT COUNT(*)
    FROM {$this->getPrefix()}groups
    WHERE
        group_id = ?
EOT;
        $result = $this->getConnection()->executeQuery($query, array($groupId));

        return $result->fetchColumn() > 0;
    }

    private function getGroup(User $user)
    {

        foreach (self::$translations as $role => $group) {
            if ($user->hasRole($role)) {
                return $group;
            }
        }

        // ROLE_USER
        return 10021;
    }

    private function findWithGroup(User $user)
    {
        $query = <<<EOT
SELECT user_id, group_id
    FROM {$this->getPrefix()}users
    WHERE username = ?
EOT;

        return $this
            ->getConnection()
            ->fetchAssoc($query, array ($user->getUsername()))
        ;
    }

    private function addToGroup($userId, $groupId)
    {
        $this->addRole($userId, $groupId);
        if ($this->inGroup($userId, $groupId)) {
            return false;
        }

        $query = <<<EOT
INSERT INTO {$this->getPrefix()}user_group
    (group_id, user_id, user_pending)
    VALUES
    (?,?,0)
EOT;

        $this->getConnection()->executeUpdate($query, array($groupId, $userId));
    }

    private function addRole($userId, $groupId)
    {
        if ($this->hasRole($userId, $groupId)) {
            return false;
        }

        $query = <<<EOT
INSERT INTO {$this->getPrefix()}acl_users
    (auth_role_id, user_id)
    VALUES
    (?,?)
EOT;

        $this->getConnection()->executeUpdate($query, array($groupId, $userId));
    }

    private function hasRole($userId, $groupId)
    {
        $query = <<<EOT
SELECT COUNT(*)
    FROM {$this->getPrefix()}acl_users
    WHERE
            auth_role_id = ?
        AND user_id = ?
EOT;
        $result = $this->getConnection()->executeQuery($query, array($groupId, $userId));

        return $result->fetchColumn() > 0;
    }
}
