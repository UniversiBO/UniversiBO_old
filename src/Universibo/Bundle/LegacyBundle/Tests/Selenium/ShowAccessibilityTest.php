<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

class ShowAccessibilityTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $sentences = array (
                'Dichiarazione di accessibilit',
                'vai all\'homepage',
                'vai al forum',
        );

        $this->openPrefix('/accessibilita');
        $this->assertSentences($sentences);
    }
}
