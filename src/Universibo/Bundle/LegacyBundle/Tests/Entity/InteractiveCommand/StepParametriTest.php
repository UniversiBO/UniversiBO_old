<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity\InteractiveCommand;

use Universibo\Bundle\MainBundle\Tests\Entity\EntityTest;
use Universibo\Bundle\LegacyBundle\Entity\InteractiveCommand\StepParametri;

class StepParametriTest extends EntityTest
{
    /**
     * @var StepParametri
     */
    private $entity;

    protected function setUp()
    {
        $this->entity = new StepParametri();
    }

    /**
     * @dataProvider accessorDataProvider
     *
     * @param string $name
     * @param mixed  $value
     */
    public function testAccessors($name, $value)
    {
        $this->autoTestAccessor($this->entity, $name, $value, true);
    }

    public function accessorDataProvider()
    {
        return array(
                array('callbackName', 'call_hello'),
                array('id', rand()),
                array('paramName', 'hello'),
                array('paramValue', rand().''),
        );
    }
}
