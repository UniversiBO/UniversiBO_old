<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity;

abstract class UniversiBOEntityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param object $object
     * @param string $name
     * @param mixed  $value
     */
    protected function autoTestAccessor($object, $fieldName, $value, $fluent = false)
    {
        $return = $this->setValue($object, $fieldName, $value);

        if ($fluent) {
            $this->assertEquals($object, $return, 'Expecting fluent interface');
        }

        $this->assertEquals($value, $this->getValue($object, $fieldName), 'getter value');
    }

    /**
     * @param  object                    $object
     * @param  string                    $fieldName
     * @throws \InvalidArgumentException
     */
    protected function getValue($object, $fieldName)
    {
        //method names are case insensitive
        $getters = array('get'.$fieldName,'is'.$fieldName,'has'.$fieldName);

        $class = new \ReflectionClass($object);

        foreach ($getters as $getter) {
            if ($class->hasMethod($getter)) {
                return $object->{$getter}();
            }
        }

        throw new \InvalidArgumentException('Getter not found');
    }

    /**
     * @param  object                    $object
     * @param  string                    $fieldName
     * @throws \InvalidArgumentException
     */
    protected function setValue($object, $fieldName, $value)
    {
        //method names are case insensitive
        $setter = 'set'.$fieldName;

        $class = new \ReflectionClass($object);

          if (!$class->hasMethod($setter)) {
               throw new \InvalidArgumentException('Setter not found');
           }

        return $object->{$setter}($value);
    }
}
