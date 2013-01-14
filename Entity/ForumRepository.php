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
     * @param Forum $forum
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

       return $this->rowToForum($row);
    }

    /**
     * Finds a forum by name
     *
     * @param  string     $name
     * @return Forum|null
     */
    public function findOneByName($name)
    {
        $query = 'SELECT * FROM ' . $this->getPrefix().'forums WHERE forum_name = ?';

        $row = $this
            ->getConnection()
            ->fetchAssoc($query, array($name))
        ;

        return $this->rowToForum($row);
    }

    public function delete(Forum $forum)
    {
        $leftRight = $this->getLeftRight($id = $forum->getId());

        if ($leftRight['left_id'] !== $leftRight['right_id'] - 1) {
            throw new \LogicException('Delete nested forums first!');
        }

        $this->incrementLeft($leftRight['left_id'], -2);
        $this->incrementRight($leftRight['right_id'], -2);

        $query = 'DELETE FROM '.$this->getPrefix().'forums WHERE forum_id = ?';

        return $this
            ->getConnection()
            ->executeUpdate($query, array($id)) > 0
        ;
    }

     /**
     * Creates a new forum
     *
     * @param Forum $forum
     */
    private function create(Forum $forum)
    {
        $leftRight = $this->getLeftRight($parentId = $forum->getParentId());

        if ($leftRight === null) {
            $left = 1;
            $right = 2;
        } else {
            if ($parentId !== 0) {
                $left = $leftRight['right_id'];

                $this->incrementRight($leftRight['right_id'], 2);
                $this->incrementLeft($leftRight['right_id'] + 1);
            } else {
                $left = $leftRight['right_id'] + 1;
            }

            $right = $left + 1;
        }

        $query = <<<EOT
INSERT INTO {$this->getPrefix()}forums
(
    forum_name,
    forum_desc,
    forum_type,
    parent_id,
    left_id,
    right_id
)
VALUES (?, ?, ?, ?, ?, ?)

EOT;
        $this
            ->getConnection()
            ->executeUpdate($query, array(
                $forum->getName(),
                $forum->getDescription(),
                $forum->getType(),
                $forum->getParentId(),
                $left,
                $right
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
     * Gets forum's left_id and right_id
     *
     * @param  integer $id
     * @return array
     */
    private function getLeftRight($id)
    {
        if ($this->getMaxId() === null) {
            return null;
        }

        $id = intval($id);

        $params = array();

        if (0 !== $id) {
            $query = 'SELECT left_id, right_id FROM ' . $this->getPrefix() . 'forums WHERE forum_id = ?';
            $params[] = $id;
        } else {
            $query = 'SELECT MIN(left_id) AS left_id, MAX(right_id) AS right_id FROM ' . $this->getPrefix() . 'forums';
        }

        return $this
            ->getConnection()
            ->fetchAssoc($query, $params) ?: null
        ;
    }

    private function incrementRight($fromValue, $amount = 1)
    {
        return $this->incrementField($fromValue, 'right_id', $amount);
    }

    private function incrementLeft($fromValue, $amount = 1)
    {
        return $this->incrementField($fromValue, 'right_id', $amount);
    }

    private function incrementField($fromValue, $field, $amount)
    {
        $query = <<<EOT
UPDATE {$this->getPrefix()}forums
    SET {$field} = {$field} + {$amount}
    WHERE
        {$field} >= ?
EOT;

        return $this
            ->getConnection()
            ->executeUpdate($query, array($fromValue)) > 0;
    }

    /**
     * Converts a row array to a forum object
     *
     * @param  mixed      $row
     * @return Forum|null
     */
    private function rowToForum($row)
    {
        if (!is_array($row)) {
            return null;
        }

        $forum = new Forum();

        return $forum
            ->setId($row['forum_id'])
            ->setName($row['forum_name'])
            ->setDescription($row['forum_desc'])
            ->setType($row['forum_type'])
            ->setParentId($row['parent_id'])
        ;
    }
}
