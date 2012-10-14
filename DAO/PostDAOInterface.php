<?php
namespace Universibo\Bundle\ForumBundle\DAO;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
interface PostDAOInterface
{
    public function getLatestPosts($forumId, $limit);
}
