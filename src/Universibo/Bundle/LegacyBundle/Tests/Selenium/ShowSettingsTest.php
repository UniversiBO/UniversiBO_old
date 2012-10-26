<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class ShowSettingsTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testNotLogged()
    {
        $this->logout();
        $this->openPrefix('/my/settings/');

        $this->assertSentences(array('Login', 'username', 'password'));
    }

    public function testStudent()
    {
        $this->login(TestConstants::STUDENT_USERNAME);
        $this->openPrefix('/my/settings/');

        $this->assertSentences($this->getBaseSentences());
        $this->assertNotSentences($this->getAdminSentences());
    }

    public function testAdmin()
    {
        $sentences = $this->getBaseSentences();

        $sentences[] = 'Docenti da contattare';
        $sentences[] = 'DB Postgresql locale';

        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/my/settings/');

        $this->assertSentences(array_merge($this->getBaseSentences(), $this->getAdminSentences()));
    }

    private function getBaseSentences()
    {
        return array (
                'I miei file',
                'Profilo',
                'Modifica MyUniversiBO',
                'Mail di ateneo',
        );
    }

    private function getAdminSentences()
    {
        return array (
                'Docenti da contattare',
                'DB Postgresql locale'
        );
    }
}
