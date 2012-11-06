<?php

namespace Universibo\Bundle\WebsiteBundle\Security\User;

use Universibo\Bundle\ShibbolethBundle\Security\User\ShibbolethUserProviderInterface;

class UniversiboUserProvider implements ShibbolethUserProviderInterface
{
    public function loadUserByClaims(array $claims)
    {
    }
}
