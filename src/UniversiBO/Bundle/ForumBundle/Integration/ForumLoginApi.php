<?php

namespace UniversiBO\Bundle\ForumBundle\Integration;

use UniversiBO\Bundle\LegacyBundle\App\User;

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