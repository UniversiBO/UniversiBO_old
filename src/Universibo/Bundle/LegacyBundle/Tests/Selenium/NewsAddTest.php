<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

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

        $this->openPrefix('/news/add/11162');

        $this->type('name=f7_titolo', 'News title');
        $this->type('name=f7_testo', 'News text');
        $this->clickAndWait('name=f7_submit');

        $this->assertSentence('inserita con successo.');
    }
}
