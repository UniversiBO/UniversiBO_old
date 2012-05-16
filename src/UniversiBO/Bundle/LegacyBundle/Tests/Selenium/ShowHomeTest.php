<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class ShowHomeTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $sentences = array (
                'Benvenuto in UniversiBO!',
                'la nuova versione della community e degli strumenti per la didattica ideato dagli studenti'
        );

        $this->open('/v2.php?do=ShowHome');

        foreach($sentences as $sentence) {
            self::assertTrue($this->isTextPresent($sentence));
        }
    }
}
