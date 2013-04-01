<?php
/**
 * UsernameUserFormatterTest class file
 */
namespace Universibo\Bundle\MainBundle\Tests\Format;

use PHPUnit_Framework_TestCase;
use Universibo\Bundle\CoreBundle\Entity\Person;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\MainBundle\Format\GivenNameSurnameUserFormatter;

/**
 * Class UsernameUserFormatterTest
 * @package Universibo\Bundle\MainBundle\Tests\Format
 */
class GivenNameSurnameUserFormatterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var GivenNameSurnameUserFormatter
     */
    private $formatter;

    protected function setUp()
    {
        $this->formatter = new GivenNameSurnameUserFormatter();
    }

    /**
     * Tests if name matches
     */
    public function testGetName()
    {
        $this->assertEquals('given_name_surname', $this->formatter->getName(), 'Formatter name should match');
    }

    /**
     * Test username
     */
    public function testPlainBehaviour()
    {
        $user = new User();
        $person = new Person();
        $user->setPerson($person);
        $person->setGivenName('Mario');
        $person->setSurname('Rossi');

        $expected = 'Mario Rossi';
        $this->assertEquals($expected, $this->formatter->format($user), 'Formatted string should match');
    }
}
