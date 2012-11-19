<?php

namespace Universibo\Bundle\ForumBundle\DAO;

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

    public function create($userId, $ip, $userAgent, $sessionId = null)
    {
        $query = <<<EOT
INSERT
    INTO {$this->getPrefix()}sessions
    (
        session_id,
        session_user_id,
        session_last_visit,
        session_start,
        session_time,
        session_ip,
        session_browser
    )
    VALUES
        (?, ?, ?, ?, ?, ?, ?)
EOT;

        if (is_null($sessionId) || !preg_match('/^[a-f0-9]+$/i', $sessionId)) {
            $sessionId = md5(uniqid(rand(), 1));
        } else {
            $this->delete($sessionId);
        }

        $this->getConnection()->executeUpdate($query, array(
            $sessionId,
            $userId,
            $time = time(),
            $time,
            $time,
            $ip,
            substr($userAgent, 0, 150)
        ));

        return $sessionId;
    }

    public function exists($sessionId)
    {
        $conn = $this->getConnection();

        $sql = <<<EOT
SELECT COUNT(*)
    FROM {$this->getPrefix()}sessions
    WHERE
        session_id = ?
EOT;

        return $conn->fetchColumn($sql, array($sessionId)) > 0;
    }
}
