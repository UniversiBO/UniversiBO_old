<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;


abstract class UniversiBOEntityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param object $object
     * @param string $name
     * @param mixed  $value
     */
    protected function autoTestAccessor($object, $name, $value)
    {
        $setter = 'set'.ucfirst($name);
        $getter = 'get'.ucfirst($name);

        $object->{$setter}($value);

        $this->assertEquals($value, $object->{$getter}(), 'getter value');
    }
}
