<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

class ShowInsegnamentoTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $this->logout();
        $sentences = array (
                'INSEGNAMENTO DI ANALISI MATEMATICA T-1 aa. 2011/2012 OBRECHT ENRICO',
        );

        $this->openPrefix('/insegnamento/10271');
        $this->assertSentences($sentences);
    }
}
