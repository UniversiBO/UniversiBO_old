<?php

namespace Universibo\Bundle\ForumBundle\DAO;

use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class PhpBB3SessionDAO extends AbstractDAO implements SessionDAOInterface
{
    /**
     * @param  string  $id
     * @return boolean
     */
    public function delete($id)
    {
        $conn = $this->getConnection();

        $sql = <<<EOF
DELETE
    FROM {$this->getPrefix()}sessions
    WHERE
        session_id = ?
EOF;

        return $conn->executeUpdate($sql, array($id));
    }

    public function create(User $user)
    {
        // TODO method stub
    }
}
