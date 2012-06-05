<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class ShowMyUniversiBOTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testLoggedOut()
    {
        $this->deleteAllVisibleCookies();
        $this->openCommand('ShowMyUniversiBO');
        self::assertTrue($this->isTextPresent('Error!', 'Error message must be present'));
    }

    public function testLoggedIn()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openCommand('ShowMyUniversiBO');
        self::assertTrue($this->isTextPresent('My UniversiBO'));
        self::assertTrue($this->isTextPresent('Modifica MyUniversiBO'));
        self::assertTrue($this->isTextPresent('My News'));
        self::assertTrue($this->isTextPresent('My Files'));
    }
}
