<?php

namespace Universibo\Bundle\WebsiteBundle\Security\Encoder;

class Sha1Encoder extends AbstractEncoder
{
    public function encodePassword($raw, $salt)
    {
        return sha1($salt.$raw);
    }
}
