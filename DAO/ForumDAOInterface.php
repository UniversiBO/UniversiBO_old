<?php
/**
 * @copyright (c) 2012, Associazione UniversiBO
 * @license GPLv3
 */
namespace Universibo\Bundle\ForumBundle\DAO;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
interface ForumDAOInterface
{
    /**
     * Gets the maximum Forum ID
     */
    public function getMaxId();

    /**
     * Creates a new forum
     *
     * @param  string  $title
     * @param  string  $description
     * @param  integer $parentId
     * @return integer forum id
     */
    public function create($title, $description, $parentId);
}
