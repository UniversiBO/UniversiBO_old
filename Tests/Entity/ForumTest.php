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

}
