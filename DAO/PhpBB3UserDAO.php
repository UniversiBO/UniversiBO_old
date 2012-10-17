<?php

namespace Universibo\Bundle\ForumBundle\DAO;

use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class PhpBB3UserDAO extends AbstractDAO implements UserDAOInterface
{
    public function create(User $user, $group)
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

        $this->getConnection()->executeUpdate($query, array(
            $user->getUsername(),
            $user->getUsernameCanonical(),
            $user->getEmail(),
            time(),
            $group
        ));

        return $this->getConnection()->lastInsertId('phpbb_users_seq');
    }

    public function find(User $user)
    {
        $query = <<<EOT
SELECT user_id
    FROM {$this->getPrefix()}users
    WHERE username = ?
EOT;

        return $this->getConnection()->fetchColumn($query, array($user->getUsername()));
    }

    public function findOrCreate(User $user, $group)
    {
        $id = $this->find($user);

        if ($id > 0) {
            return $id;
        }

        return $this->create($user, $group);
    }

    public function update(User $user)
    {
        $query = <<<EOT
UPDATE {$this->getPrefix()}users
    SET user_email = ?
    WHERE username = ?
EOT;

        return $this->getConnection()->executeUpdate($query,
                array($user->getEmail(),  $user->getUsername())) > 0;

    }
}
