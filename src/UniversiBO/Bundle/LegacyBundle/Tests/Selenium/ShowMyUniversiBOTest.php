<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class ShowMyUniversiBOTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }
    
    public function testLoggedOut()
    {
        $this->deleteAllVisibleCookies();
        $this->open('/index.php?do=ShowMyUniversiBO');
        self::assertTrue($this->isTextPresent('Error!', 'Error message must be present'));
    }
    
    public function testLoggedIn()
    {
        $this->login('brain');
        $this->open('/index.php?do=ShowMyUniversiBO');
        self::assertTrue($this->isTextPresent('My UniversiBO'));
        self::assertTrue($this->isTextPresent('Modifica MyUniversiBO'));
        self::assertTrue($this->isTextPresent('My News'));
        self::assertTrue($this->isTextPresent('My Files'));
    }
}