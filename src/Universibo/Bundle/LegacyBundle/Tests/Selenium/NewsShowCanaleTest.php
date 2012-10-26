<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class NewsShowCanaleTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testNotLogged()
    {
        $this->logout();
        $this->openPrefix('/news/canale/1?inizio=0&qta=10');
        $this->assertNotSentence('Scrivi nuova notizia');
    }

    public function testLogged()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/news/canale/1?inizio=0&qta=10');
        $this->assertSentences(array('News','Scrivi nuova notizia'));
    }

    public function testPermalink()
    {
        $this->openPrefix('/news/canale/1?inizio=0&qta=10');
        $this->clickAndWait('link=permalink');
        $this->assertFalse($this->isTextPresent('Error'), 'Should NOT display an error message');
    }
}
