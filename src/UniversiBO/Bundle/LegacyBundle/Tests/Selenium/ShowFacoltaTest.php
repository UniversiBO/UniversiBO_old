<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class ShowFacoltaTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $sentences = array (
                'FACOLTA\' DI INGEGNERIA - 0021',
        );

        $this->open('/v2.php?do=ShowFacolta&id_canale=2');

        foreach ($sentences as $sentence) {
            self::assertTrue($this->isTextPresent($sentence));
        }
    }
}
