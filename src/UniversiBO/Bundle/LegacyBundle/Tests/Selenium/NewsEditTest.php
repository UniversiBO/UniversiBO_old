<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class NewsEditTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testEditNews()
    {
        $this->login('brain');

        $this->open('/v2.php?do=NewsEdit&id_news=10791&id_canale=1');

        $this->type('name=f8_titolo', 'News title');
        $this->type('name=f8_testo', 'News text');
        $this->clickAndWait('name=f8_submit');

        self::assertTrue($this->isTextPresent('modificata con successo.'), 'Checking for text "modificata con successo."');
    }
}
