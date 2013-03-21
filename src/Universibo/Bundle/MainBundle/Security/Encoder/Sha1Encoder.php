<?php

namespace Universibo\Bundle\MainBundle\Security\Encoder;

class Sha1Encoder extends AbstractEncoder
{
    public function encodePassword($raw, $salt)
    {
        return sha1($salt.$raw);
    }
}
