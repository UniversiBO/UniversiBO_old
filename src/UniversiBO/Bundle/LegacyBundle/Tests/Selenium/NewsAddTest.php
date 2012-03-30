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
        $this->login('lgalli');
        
        $this->open('/index.php?do=NewsAdd&id_canale=11162');
        
        $this->type('name=f7_titolo', 'News title');
        $this->type('name=f7_testo', 'News text');
        $this->clickAndWait('name=f7_submit');
        
        self::assertTrue($this->isTextPresent('inserita con successo.'), 'Checking for text "inserita con successo."');
    }
}