<?php

namespace Universibo\Bundle\ForumBundle\DAO;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class PhpBB3PostDAO extends AbstractDAO implements PostDAOInterface
{
    public function getLatestPosts($forumId, $limit)
    {
        $query = <<<EOF
SELECT t.topic_title, min(p.post_id)
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
