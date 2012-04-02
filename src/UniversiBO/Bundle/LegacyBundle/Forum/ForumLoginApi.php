<?php
namespace UniversiBO\Bundle\LegacyBundle\Forum;

use UniversiBO\Bundle\LegacyBundle\Entity\User;

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