<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

class ShowRulesTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $sentences = array (
                'Regolamento per l\'utilizzo dei servizi del sito',
                'Informativa sulla privacy',
                'Regolamento per l\'utilizzo del forum'
        );

        $this->openPrefix('/regolamento/');
        $this->assertSentences($sentences);
    }
}
