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
                'INSEGNAMENTO DI SISTEMI MOBILI M aa. 2012/2013 LAST NAME GIVEN NAME',
        );

        $this->openPrefix('/insegnamento/5');
        $this->assertSentences($sentences);
    }
}
