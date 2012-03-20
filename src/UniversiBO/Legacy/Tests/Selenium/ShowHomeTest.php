<?php
namespace UniversiBO\Legacy\Tests\Selenium;

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

        $this->open('/index.php?do=ShowHome');

        foreach($sentences as $sentence) {
            self::assertTrue($this->isTextPresent($sentence));
        }
    }
}