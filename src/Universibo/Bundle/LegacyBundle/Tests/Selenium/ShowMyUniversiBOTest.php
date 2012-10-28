<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class ShowMyUniversiBOTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testLoggedIn()
    {
        $this->login(TestConstants::STUDENT_USERNAME);
        $this->openPrefix('/my/universibo');
        self::assertTrue($this->isTextPresent('My UniversiBO'));
        self::assertTrue($this->isTextPresent('Modifica MyUniversiBO'));
        self::assertTrue($this->isTextPresent('My News'));
        self::assertTrue($this->isTextPresent('My Files'));
    }
}
