<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class ShowFileInfoTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $sentences = array (
                'Inserito da: mrscalas',
                'Inserito il: 18/11/2011',
                'Titolo: robots SEO',
                'Descrizione/abstract: robots SEO',
        );
        
        $this->open('/index.php?do=FileShowInfo&id_file=15051&id_canale=10434');
        
        $this->assertSentences($sentences);
    }
}