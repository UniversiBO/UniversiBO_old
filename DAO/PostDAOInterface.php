<?php
namespace Universibo\Bundle\ForumBundle\DAO;

use Universibo\Bundle\CoreBundle\Entity\MergeableRepositoryInterface;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
interface PostDAOInterface extends MergeableRepositoryInterface
{
    public function getLatestPosts($forumId, $limit);
}
