<?php
namespace Universibo\Bundle\LegacyBundle\Forum;

use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * @author Davide Bellettini
 */
interface ForumLoginApi
{
    public function getSidForUri();
    public function getOnlySid();
    public function getPath();
    public function login(User $user);
    public function logout();
}
