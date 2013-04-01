<?php
namespace Universibo\Bundle\MainBundle\Tests\Entity;

use Universibo\Bundle\MainBundle\Entity\Person;

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

    public function testGivenNameAccessors()
    {
        $givenName = 'Hello'.rand(1,9999);

        $this->assertSame($this->person, $this->person->setGivenName($givenName));
        $this->assertEquals($givenName, $this->person->getGivenName());
    }

    public function testSurnameAccessors()
    {
        $surname = 'Hello'.rand(1,9999);

        $this->assertSame($this->person, $this->person->setSurname($surname));
        $this->assertEquals($surname, $this->person->getSurname());
    }
}
