<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Entity\Help;

use Universibo\Bundle\CoreBundle\Tests\Entity\EntityTest;
use Universibo\Bundle\LegacyBundle\Entity\Help\Item;
use Universibo\Bundle\LegacyBundle\Entity\Help\Topic;

class TopicTest extends EntityTest
{
    /**
     * @var Item
     */
    private $topic;

    protected function setUp()
    {
        $this->topic = new Topic();
    }

    /**
     * @dataProvider accessorDataProvider
     *
     * @param string $name
     * @param mixed  $value
     */
    public function testAccessors($name, $value)
    {
        $this->autoTestAccessor($this->topic, $name, $value, true);
    }

    public function accessorDataProvider()
    {
        return array(
                array('reference', 'hello'),
                array('title', 'Hello World!'),
                array('index', rand())
        );
    }
}
