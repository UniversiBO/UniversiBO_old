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
     * Tests if username mode is supported
     */
    public function testSupportsMode()
    {
        $this->assertTrue($this->formatter->supports('given_name_surname'), 'Formatter should support given_name_surname mode');
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
        $this->assertEquals($expected, $this->formatter->format($user, 'given_name_surname'), 'Formatted string should match');
    }
}
