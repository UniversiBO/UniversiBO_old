<?php

namespace Universibo\Bundle\MainBundle\Security\Encoder;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

abstract class AbstractEncoder implements PasswordEncoderInterface
{
    public function isPasswordValid($encoded, $raw, $salt)
    {
        return $this->encodePassword($raw, $salt) === $encoded;
    }
}
