<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

use UniversiBO\Bundle\LegacyBundle\Tests\TestConstants;

class LoginTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }
    
    /**
     * @dataProvider provider
     * @param string $username
     * @param string $level
     */
    public function testLogin($username, $level)
    {
        $this->login($username);
        $this->checkLevel($level);
        $this->logout();
    }

    public function provider()
    {
        return array(
                array(TestConstants::ADMIN_USERNAME, 'Admin'),
                array(TestConstants::PROFESSOR_USERNAME, 'Docente'),
                array(TestConstants::STAFF_USERNAME, 'Personale non docente'),
                array(TestConstants::STUDENT_USERNAME, 'Studente'),
                array(TestConstants::TUTOR_USERNAME, 'Tutor'),
        );
    }

    private function checkLevel($level)
    {
        self::assertTrue($this->isTextPresent('Il tuo livello di utenza'));
        self::assertTrue($this->isTextPresent($level));
    }
}
