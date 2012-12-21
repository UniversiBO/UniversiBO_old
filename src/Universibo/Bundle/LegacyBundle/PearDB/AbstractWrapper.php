<?php

/**
 * @author Davide Bellettini
 * @license GPLv2
 */

namespace Universibo\Bundle\LegacyBundle\PearDB;

use RuntimeException;

/**
 * Base class for both wrappers
 */
abstract class AbstractWrapper
{
    /**
     * __call magic method, to throw exceptions instead of fatal rerror
     *
     * @param  string           $methodName
     * @param  mixed            $args
     * @throws RuntimeException
     */
    public function __call($name, $arguments)
    {
        throw new RuntimeException('Method '. $name. ' not implemented');
    }

    public static function __callStatic($name, $arguments)
    {
        throw new RuntimeException('Static method '. $name. ' not implemented');
    }
}
