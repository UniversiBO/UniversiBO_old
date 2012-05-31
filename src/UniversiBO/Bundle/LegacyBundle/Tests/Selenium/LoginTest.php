<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class LoginTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testLoginAdmin()
    {
        $this->login('brain');
        $this->checkLevel('Admin');
        $this->logout();
    }

    public function testLoginDocente()
    {
        $this->login('edenti');
        $this->checkLevel('Docente');
        $this->logout();
    }

    public function testLoginPersonale()
    {
        $this->login('maurizio.zani');
        $this->checkLevel('Personale non docente');
        $this->logout();
    }

    public function testLoginStudente()
    {
        $this->login('Dece');
        $this->checkLevel('Studente');
        $this->logout();
    }

    public function testLoginTutor()
    {
        $this->login('dtiles');
        $this->checkLevel('Tutor');
        $this->logout();
    }

    private function checkLevel($level)
    {
        self::assertTrue($this->isTextPresent('Il tuo livello di utenza'));
        self::assertTrue($this->isTextPresent($level));
    }
}
