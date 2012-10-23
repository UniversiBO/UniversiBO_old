<?php
namespace Universibo\Bundle\CoreBundle\Entity;

/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
interface MergeableRepositoryInterface
{
    /**
     * Counts the items owned by the given user
     *
     * @param  User    $user
     * @return integer
     */
    public function countByUser(User $user);

    /**
     * Transfers items ownership
     *
     * @param User $source
     * @param User $target
     */
    public function transferOwnership(User $source, User $target);
}
