<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity\Help;

use Universibo\Bundle\CoreBundle\Tests\Entity\EntityTest;
use Universibo\Bundle\LegacyBundle\Entity\Help\Item;
use Universibo\Bundle\LegacyBundle\Entity\Help\Reference;

class ReferenceTest extends EntityTest
{
    /**
     * @var Item
     */
    private $reference;

    protected function setUp()
    {
        $this->reference = new Reference();
    }

    /**
     * @dataProvider accessorDataProvider
     *
     * @param string $name
     * @param mixed  $value
     */
    public function testAccessors($name, $value)
    {
        $this->autoTestAccessor($this->reference, $name, $value, true);
    }

    public function accessorDataProvider()
    {
        return array(
                array('id', 'hello'),
                array('helpId', rand())
        );
    }
}
