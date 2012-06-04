<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class NewsAddTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testInsertNews()
    {
        // user who reported an issue on this
        $this->login('lgalli');

        $this->openCommand('NewsAdd','&id_canale=11162');

        $this->type('name=f7_titolo', 'News title');
        $this->type('name=f7_testo', 'News text');
        $this->clickAndWait('name=f7_submit');

        $this->assertSentence('inserita con successo.');
    }
}
