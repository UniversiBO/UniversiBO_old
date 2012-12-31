<?php
/**
 * @copyright (c) 2012, Associazione UniversiBO
 * @license GPLv3 or later
 */

namespace Universibo\Bundle\ForumBundle\DAO;

/**
 * Forum DAO.
 * It's quite complicated because of Nested Set model
 *
 * @link http://en.wikipedia.org/wiki/Nested_set_model Nested Set
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
class PhpBB3ForumDAO extends AbstractDAO implements ForumDAOInterface
{
    /**
     * Creates a new forum
     *
     * @todo implementation
     * @param  string  $title
     * @param  string  $description
     * @param  integer $type
     * @param  integer $parentId
     * @return integer forum id
     */
    public function create($title, $description, $type, $parentId = 0)
    {
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
     * Renames a forum
     *
     * @param  integer $forumId
     * @param  string  $name
     * @return boolean
     */
    public function rename($forumId, $name)
    {
        $query = <<<EOT
UPDATE {$this->getPrefix()}forums
    SET
        forum_name = ?
    WHERE
        forum_id = ?
EOT;

        return $this
            ->getConnection()
            ->executeUpdate($query, array(
                $name,
                $forumId
            )) > 0
        ;
    }

    /**
     * Sorts forums alphabetically
     *
     * @todo implementation
     * @param  integer $parentId
     * @return integer affected forums count
     */
    public function sortForumsAlphabetically($parentId)
    {
    }
}
