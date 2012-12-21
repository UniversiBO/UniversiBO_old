<?php
/**
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
namespace Universibo\Bundle\LegacyBundle\PearDB;

/**
 * DB from Pear::DB wrapper
 */
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
