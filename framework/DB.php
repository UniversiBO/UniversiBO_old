<?php

use Universibo\Bundle\LegacyBundle\PearDB\AbstractWrapper;

class DB extends AbstractWrapper
{
    /**
     * Is error will always return false, because otherwise en exception
     * would be thrown
     * 
     * @return boolean
     */
    public static function isError()
    {
        return false;
    }
}
