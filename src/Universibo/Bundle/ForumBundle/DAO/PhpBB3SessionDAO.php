<?php

namespace Universibo\Bundle\ForumBundle\DAO;

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
}
