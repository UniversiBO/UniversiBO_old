<?php

namespace Universibo\Bundle\ForumBundle\DAO;

use Doctrine\DBAL\Connection;
use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class PhpBB3PostDAO extends AbstractDAO implements PostDAOInterface
{
    /**
     * @var UserDAOInterface
     */
    private $userDAO;

    public function __construct(Connection $connection, UserDAOInterface $userDAO)
    {
        parent::__construct($connection);

        $this->userDAO = $userDAO;
    }

    public function countByUser(User $user)
    {
        $userId = $this->userDAO->findOrCreate($user);

        $query = <<<EOT
SELECT COUNT(*)
    FROM
        {$this->getPrefix()}posts p
    WHERE
        p.poster_id = ?
EOT;
        $result = $this
            ->getConnection()
            ->executeQuery($query, array($userId))
        ;

        return $result->fetchColumn();
    }

    public function transferOwnership(User $source, User $target)
    {
        $sourceId =  $this->userDAO->findOrCreate($source);
        $targetId =  $this->userDAO->findOrCreate($target);

        $query = <<<EOT
UPDATE {$this->getPrefix()}posts
    SET
        poster_id = ?
    WHERE
        poster_id = ?
EOT;

        return $this
            ->getConnection()
            ->executeUpdate($query, array($targetId, $sourceId))
        ;
    }

    public function getLatestPosts($forumId, $limit)
    {
        $query = <<<EOF
SELECT t.topic_title, min(p.post_id) AS post_id
    FROM
        {$this->getPrefix()}posts p,
        {$this->getPrefix()}topics t
    WHERE
        t.topic_id = p.topic_id
    AND p.forum_id = ?
    AND p.post_id IN
    (
        SELECT pp.post_id
            FROM {$this->getPrefix()}posts pp
            WHERE t.topic_id = pp.topic_id
            ORDER BY pp.post_time ASC
    )
    GROUP BY t.topic_title
    ORDER BY max(p.post_id) DESC
    LIMIT ?
EOF;

        $result = $this
            ->getConnection()
            ->executeQuery($query, array($forumId, $limit))
        ;

        return $result->fetchAll();
    }
}
