<?php
/**
 * UserFormatterInterface file
 */
namespace Universibo\Bundle\MainBundle\Format;

use Universibo\Bundle\MainBundle\Entity\User;

/**
 * Interface UserFormatterInterface
 * @package Universibo\Bundle\MainBundle\Format
 */
interface UserFormatterInterface
{
    /**
     * Returns the format name
     *
     * @return string
     */
    public function getName();

    /**
     * Converts a User to string according to $mode
     *
     * @param  User   $user
     * @return string
     */
    public function format(User $user);
}
