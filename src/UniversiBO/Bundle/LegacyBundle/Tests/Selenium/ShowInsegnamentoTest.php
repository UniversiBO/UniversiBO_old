<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class ShowInsegnamentoTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $sentences = array (
                'INSEGNAMENTO DI ANALISI MATEMATICA T-1 aa. 2011/2012 OBRECHT ENRICO',
        );

        $this->open('/v2.php?do=ShowInsegnamento&id_canale=10271');

        foreach($sentences as $sentence) {
            self::assertTrue($this->isTextPresent($sentence));
        }
    }
}
