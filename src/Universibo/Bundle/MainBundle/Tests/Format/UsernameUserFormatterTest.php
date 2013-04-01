<?php
/**
 * UsernameUserFormatterTest class file
 */
namespace Universibo\Bundle\MainBundle\Tests\Format;

use PHPUnit_Framework_TestCase;
use Universibo\Bundle\MainBundle\Entity\User;
use Universibo\Bundle\MainBundle\Format\UsernameUserFormatter;

/**
 * Class UsernameUserFormatterTest
 * @package Universibo\Bundle\MainBundle\Tests\Format
 */
class UsernameUserFormatterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var UsernameUserFormatter
     */
    private $formatter;

    protected function setUp()
    {
        $this->formatter = new UsernameUserFormatter();
    }

    /**
     * Tests if name matches
     */
    public function testGetName()
    {
        $this->assertEquals('username', $this->formatter->getName(), 'Formatter name should match');
    }

    /**
     * Test username
     */
    public function testPlainBehaviour()
    {
        $username = 'hello';

        $user = new User();
        $user->setUsername($username);

        $this->assertEquals($username, $this->formatter->format($user), 'Username should match');
    }
}
