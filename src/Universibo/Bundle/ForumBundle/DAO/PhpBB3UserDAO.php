<?php

namespace Universibo\Bundle\ForumBundle\DAO;

use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class PhpBB3UserDAO extends AbstractDAO implements UserDAOInterface
{
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
    (?, ?, ?, ?, 3)
EOT;

        $this->getConnection()->executeUpdate($query, array(
            $user->getUsername(),
            $user->getUsernameCanonical(),
            $user->getEmail(),
            time()
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

    public function findOrCreate(User $user)
    {
        $id = $this->find($user);

        if ($id > 0) {
            return $id;
        }

        return $this->create($user);
    }

    public function update(User $user)
    {
    }
}
