<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;
use UniversiBO\Bundle\LegacyBundle\Tests\TestConstants;

class ShowCanaleTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openCommand('ShowCanale', '&id_canale=2219');
        $this->assertSentences(
                        array('Area Laureati', 'News', 'Scrivi nuova notizia',
                                'Mostra tutte le news', 'Files',
                                'Invia un nuovo file', 'Gestione file'));
    }
}
