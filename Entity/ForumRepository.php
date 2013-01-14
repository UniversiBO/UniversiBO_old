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

    public function findByParentId($parentId)
    {
        $forums = array();

        foreach ($this->findByParentIdArray($parentId) as $row) {
            $forums[] = $this->rowToForum($row);
        }

        return $forums;
    }

    private function findByParentIdArray($parentId)
    {
        $sql = <<<EOT
SELECT *
    FROM {$this->getPrefix()}forums
    WHERE
        parent_id = ?
    ORDER BY
        left_id
EOT;

        return $this
            ->getConnection()
            ->fetchAll($sql, array($parentId))
        ;
    }

    public function sortAlphabetically($parentId)
    {
        $array = $this->findByParentIdArray($parentId);

        $comparingFunction = function($a, $b) {
            return strcasecmp($a['forum_name'], $b['forum_name']);
        };

        $changed = array();

        $swappingFunction = function(&$a, &$b) use (&$changed) {
            $c = $a;
            $a = $b;
            $b = $c;

            $this->swapForums($a, $b, $changed);
        };

        $this->bubbleSort($array, $comparingFunction, $swappingFunction);

        foreach ($changed as $forum) {
            $this->setLeftRight($forum['forum_id'], $forum);
        }
    }

    private function swapForums(&$forum1, &$forum2, array &$changed)
    {
        if ($forum1['parent_id'] !== $forum2['parent_id']) {
            throw new \LogicException('Parent forum not matching');
        }

        $left1 = $forum1['left_id'];
        $right1 = $forum1['right_id'];

        $forum1['left_id'] = $forum2['left_id'];
        $forum1['right_id'] = $forum2['right_id'];

        $forum2['left_id'] = $left1;
        $forum2['right_id'] = $right1;

        $changed[$forum1['forum_id']] = $forum1;
        $changed[$forum2['forum_id']] = $forum2;
    }

    /**
     * Sets left and right
     *
     * @param  Forum|integer $forum
     * @param  array         $leftRight
     * @return boolean
     */
    private function setLeftRight($forumId, array $leftRight)
    {
        if ($forumId instanceof Forum) {
            $forumId = $forumId->getId();
        }

        $query = <<<EOT
UPDATE {$this->getPrefix()}forums
    SET
        left_id = ?,
        right_id = ?
    WHERE
        forum_id = ?
EOT;

        return $this
            ->getConnection()
            ->executeUpdate($query, array(
                $leftRight['left_id'],
                $leftRight['right_id'],
                $forumId
            )) > 0
        ;
    }

    private function bubbleSort(array $array, $comparingFunction, $swappingFunction)
    {
        $n = count($array);

        do {
            $swapped = false;

            for ($i=1; $i<$n; $i++) {
                if ($comparingFunction($array[$i-1], $array[$i]) > 0) {
                    $swappingFunction($array[$i-1], $array[$i]);
                    $swapped = true;
                }
            }
        } while ($swapped);
    }
}
