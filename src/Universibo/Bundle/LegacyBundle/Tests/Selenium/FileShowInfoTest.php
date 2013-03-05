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
                'Inserito da: admin',
                'Inserito il: 5/03/2013',
                'Titolo: Test file',
                'Descrizione/abstract: robots SEO',
        );

        $this->openPrefix('/file/1/');

        $this->assertSentences($sentences);
    }
}
