<?php

namespace Universibo\Bundle\ForumBundle\Tests\Entity;

use PHPUnit_Framework_TestCase;
use Universibo\Bundle\ForumBundle\Entity\Forum;

class ForumTest extends PHPUnit_Framework_TestCase
{
    /**
     * Forum
     * @var Forum
     */
    private $forum;

    protected function setUp()
    {
        $this->forum = new Forum();
    }

    public function testIdAccessors()
    {
        $forum = $this->forum;

        $id = rand(1, 200);

        $this->assertSame($forum, $forum->setId($id));

        $this->assertEquals($id, $forum->getId());
    }

    public function testNameAccessors()
    {
        $forum = $this->forum;

        $name = 'Forum name';

        $this->assertSame($forum, $forum->setName($name));

        $this->assertEquals($name, $forum->getName());
    }

    public function testDescriptionAccessors()
    {
        $forum = $this->forum;

        $description = 'Forum description';

        $this->assertSame($forum, $forum->setDescription($description));

        $this->assertEquals($description, $forum->getDescription());
    }

    public function testTypeAccessors()
    {
        $forum = $this->forum;

        $type = Forum::TYPE_CATEGORY;

        $this->assertSame($forum, $forum->setType($type));
        $this->assertEquals($type, $forum->getType());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidType()
    {
        $this->forum->setType(4);
    }

    public function testParentIdAccessors()
    {
        $forum = $this->forum;

        $id = rand(1, 200);

        $this->assertSame($forum, $forum->setParentId($id));

        $this->assertEquals($id, $forum->getParentId());
    }

    public function testParentIdDefaultValue()
    {
        $this->assertSame(0, $this->forum->getParentId());
    }
}
