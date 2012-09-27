<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class ShowUserTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testNotAllowed()
    {
        $this->login(TestConstants::STUDENT_USERNAME);

        $this->openPrefix('/user/105');

        $this->assertSentence('Non ti e` permesso visualizzare la scheda dell\'utente');
    }

    public function testAllowed()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/user/105');

        $this->assertSentences(array('Utente: fgiardini', 'Livello: Admin'));
    }
}
