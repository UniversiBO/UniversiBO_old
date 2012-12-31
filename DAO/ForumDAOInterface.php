<?php
/**
 * @copyright (c) 2012, Associazione UniversiBO
 * @license GPLv3 or later
 */
namespace Universibo\Bundle\ForumBundle\DAO;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
interface ForumDAOInterface
{
    /**
     * Category forum (e.g. no posts inside)
     */
    const TYPE_CATEGORY = 0;

    /**
     * Plain forum
     */
    const TYPE_FORUM = 1;

    /**
     * Gets the maximum Forum ID
     *
     * @return integer max forum ID
     */
    public function getMaxId();

    /**
     * Creates a new forum
     *
     * @param  string  $title
     * @param  string  $description
     * @param  integer $type        either TYPE_CATEGORY or TYPE_FORUM
     * @param  integer $parentId    parent forum, 0 is the root
     * @return integer forum id
     */
    public function create($title, $description, $type, $parentId = 0);

    /**
     * Renames a forum
     *
     * @param  integer $forumId
     * @param  string  $name
     * @return boolean true if forum exists, false otherwise
     */
    public function rename($forumId, $name);
}
