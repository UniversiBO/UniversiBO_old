<?php
namespace UniversiBO\Legacy\Tests;

class ShowCreditsTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $sentences = array (
                'Credits',
                ' stato realizzato e funziona utilizzando internamente solo software libero e open source e appoggiandosi alle strutture rese disponibili dall\'Ateneo',
        );

        $this->open('/index.php?do=ShowCredits');

        foreach($sentences as $sentence) {
            self::assertTrue($this->isTextPresent($sentence));
        }
    }
}