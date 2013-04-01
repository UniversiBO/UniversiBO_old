<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity\Help;

use Universibo\Bundle\MainBundle\Tests\Entity\EntityTest;
use Universibo\Bundle\LegacyBundle\Entity\Help\Item;

class ItemTest extends EntityTest
{
    /**
     * @var Item
     */
    private $item;

    protected function setUp()
    {
        $this->item = new Item();
    }

    /**
     * @dataProvider accessorDataProvider
     *
     * @param string $name
     * @param mixed  $value
     */
    public function testAccessors($name, $value)
    {
        $this->autoTestAccessor($this->item, $name, $value, true);
    }

    public function accessorDataProvider()
    {
        return array(
                array('id', rand()),
                array('title', 'Hello World!'),
                array('content', 'Hello World!'),
                array('lastEdit', rand()),
                array('index', rand())
        );
    }
}
