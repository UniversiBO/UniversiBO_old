<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity\InteractiveCommand;

use Universibo\Bundle\LegacyBundle\Tests\Entity\UniversiBOEntityTest;
use Universibo\Bundle\LegacyBundle\Entity\InteractiveCommand\StepLog;

class StepLogTest extends UniversiBOEntityTest
{
    /**
     * @var StepLog
     */
    private $entity;

    protected function setUp()
    {
        $this->entity = new StepLog();
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
                array('id', rand()),
                array('idUtente', rand()),
                array('dataUltimaInterazione', rand()),
                array('nomeClasse', 'stdClass'),
                array('esitoPositivo', 'S'),
        );
    }
}
