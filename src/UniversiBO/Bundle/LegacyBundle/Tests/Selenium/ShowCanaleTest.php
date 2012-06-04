<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;
class ShowCanaleTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $this->login('brain');
        $this->open('/v2.php?do=ShowCanale&id_canale=2219');
        $this->assertSentences(
                        array('Area Laureati', 'News', 'Scrivi nuova notizia',
                                'Mostra tutte le news', 'Files',
                                'Invia un nuovo file', 'Gestione file'));
    }
}
