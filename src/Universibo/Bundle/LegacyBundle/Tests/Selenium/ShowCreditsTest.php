<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

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

        $this->openPrefix('/credits');
        $this->assertSentences($sentences);
    }
}
