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
    user_regdate
)
VALUES
    (?, ?, ?, ?)
EOT;

        return $this->getConnection()->executeUpdate($query, array(
            $user->getUsername(),
            $user->getUsernameCanonical(),
            $user->getEmail(),
            time()
        ));
    }

    public function exists(User $user)
    {
        $query = <<<EOT
SELECT COUNT(*)
    FROM {$this->getPrefix()}users
    WHERE username = ?
EOT;

        return $this->getConnection()->fetchColumn($query, array($user->getUsername())) > 0;
    }

    public function update(User $user)
    {
    }
}
