<?php
namespace Universibo\Bundle\ForumBundle\DAO;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
interface SessionDAOInterface
{
    /**
     * @param int $id
     */
    public function delete($id);
}
