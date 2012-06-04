<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

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

        $this->openCommand('ShowCredits');
        $this->assertSentences($sentences);
    }
}
