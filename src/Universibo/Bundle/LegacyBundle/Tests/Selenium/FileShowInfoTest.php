<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

class FileShowInfoTest extends UniversiBOSeleniumTestCase
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

        $this->openPrefix('/file/15051/');

        $this->assertSentences($sentences);
    }
}
