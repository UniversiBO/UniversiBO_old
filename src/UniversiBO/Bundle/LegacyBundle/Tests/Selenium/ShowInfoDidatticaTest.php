<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

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
                'GESTIONE DELL\'INNOVAZIONE E DEI PROGETTI M',
                'aa. 2011/2012',
                'MUNARI FEDERICO',
                'Obiettivi del corso',
                'Programma d\'esame',
                'Materiale didattico e testi consigliati',
                'Modalità d\'esame',
                'Appelli d\'esame',
        );

        $this->openCommand('ShowInfoDidattica','&id_canale=10507');
        $this->assertSentences($sentences);
    }
}
