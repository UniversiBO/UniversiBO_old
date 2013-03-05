<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;
use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class ShowCanaleTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/canale/2/');
        $this->assertSentences(
                        array('Test', 'News', 'Scrivi nuova notizia',
                                 'Files',
                                'Invia un nuovo file', 'Gestione file'));
    }
}
