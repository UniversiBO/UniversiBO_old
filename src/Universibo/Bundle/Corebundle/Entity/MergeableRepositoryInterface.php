<?php
/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
namespace Universibo\Bundle\Corebundle\Entity;

/**
 * Interface implemented by repositories which want to merge entities
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
