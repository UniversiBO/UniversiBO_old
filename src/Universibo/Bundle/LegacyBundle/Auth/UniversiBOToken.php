<?php

namespace Universibo\Bundle\LegacyBundle\Auth;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class UniversiBOToken extends AbstractToken
{
    private $id;

    public function getCredentials()
    {
        return '';
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}
