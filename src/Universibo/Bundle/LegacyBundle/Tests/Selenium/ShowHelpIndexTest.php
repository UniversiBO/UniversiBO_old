<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

class ShowHelpIndexTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $sentences = array (
                'Come faccio a navigare nel sito?',
                'Cos\'e\' la mail d\'ateneo?',
                'Perche\' devo avere la mail di Ateneo per iscrivermi ad UniversiBO?',
                'Come faccio ad attivare la mia mail d\'Ateneo?'
        );

        $this->openPrefix('/help');
        $this->assertSentences($sentences);
    }
}
