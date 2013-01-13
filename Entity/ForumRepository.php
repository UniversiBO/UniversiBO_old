<?php
/**
 * @copyright (c) 2013, Associazione UniversiBO
 * @license http://opensource.org/licenses/gpl-2.0.php GPLv2
 */

namespace Universibo\Bundle\ForumBundle\Entity;

/**
 * Forum Repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class ForumRepository extends Repository
{
    /**
     * Saves a forum
     *
     * @param \Universibo\Bundle\ForumBundle\Entity\Forum $forum
     */
    public function save(Forum $forum)
    {
        if (null === $forum->getId()) {
            $this->create($forum);
        } else {
            $this->update($forum);
        }
    }

    /**
     * Creates a new forum
     *
     * @param Forum $forum
     */
    private function create(Forum $forum)
    {
        $query = <<<EOT
INSERT INTO {$this->getPrefix()}forums
(
    forum_name,
    forum_desc,
    forum_type,
    parent_id
)
VALUES (?, ?, ?, ?)

EOT;
        $this
            ->getConnection()
            ->executeUpdate($query, array(
                $forum->getName(),
                $forum->getDescription(),
                $forum->getType(),
                $forum->getParentId()
            ))
        ;

        $id = $this
            ->getConnection()
            ->lastInsertId($this->getPrefix().'forums_seq')
        ;

        $forum->setId($id);
    }

    /**
     * Updates a forum
     *
     * @param Forum $forum
     */
    private function update(Forum $forum)
    {
        $query = <<<EOT
UPDATE {$this->getPrefix()}forums
    SET
        forum_name = ?,
        forum_desc = ?,
        parent_id = ?,
        forum_type = ?
    WHERE
        forum_id = ?
EOT;

        return $this
            ->getConnection()
            ->executeUpdate($query, array(
                $forum->getName(),
                $forum->getDescription(),
                $forum->getParentId(),
                $forum->getType(),
                $forum->getId()
            )) > 0
        ;
    }

    /**
     * Gets the max forum id
     *
     * @return integer|null
     */
    public function getMaxId()
    {
        $query = "SELECT MAX(forum_id) FROM {$this->getPrefix()}forums";

        return $this
            ->getConnection()
            ->fetchColumn($query)
            ?: null
        ;
    }

    /**
     * Finds a forum by id
     *
     * @param  integer    $id
     * @return Forum|null
     */
    public function find($id)
    {
       $query = 'SELECT * FROM ' . $this->getPrefix().'forums WHERE forum_id = ?';

       $row = $this
           ->getConnection()
           ->fetchAssoc($query, array($id))
       ;

       if (!$row) {
           return null;
       }

       $forum = new Forum();

       $forum
           ->setId($row['forum_id'])
           ->setName($row['forum_name'])
           ->setDescription($row['forum_desc'])
           ->setType($row['forum_type'])
           ->setParentId($row['parent_id'])
       ;

       return $forum;
    }
}
