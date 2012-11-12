<?php

namespace Universibo\Bundle\WebsiteBundle\Security\Encoder;

class Md5Encoder extends AbstractEncoder
{
    public function encodePassword($raw, $salt)
    {
        return md5($salt.$raw);
    }
}
