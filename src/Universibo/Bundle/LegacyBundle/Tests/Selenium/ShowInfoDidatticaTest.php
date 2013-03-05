<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

class ShowInfoDidatticaTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $sentences = array (
                'INSEGNAMENTO DI',
                'SISTEMI MOBILI M aa. 2012/2013',
                'aa. 2012/2013',
                'LAST NAME GIVEN NAME',
                'Obiettivi del corso',
                'Programma d\'esame',
                'Materiale didattico e testi consigliati',
                'Modalità d\'esame',
                'Appelli d\'esame',
        );

        $this->openPrefix('/insegnamento/5/info');
        $this->assertSentences($sentences);
    }
}
