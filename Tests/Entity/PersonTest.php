<?php
namespace Universibo\Bundle\CoreBundle\Tests\Entity;

use Universibo\Bundle\CoreBundle\Entity\Person;

class PersonTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Person
     */
    private $person;

    protected function setUp()
    {
        $this->person = new Person();
    }

    public function testUniboIdAccessors()
    {
        $uniboId = rand(1,9999);

        $this->assertSame($this->person, $this->person->setUniboId($uniboId));
        $this->assertEquals($uniboId, $this->person->getUniboId());
    }
}
