<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

class ShowContactsTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $sentences = array (
                'SbiellONE',
                'bulbis',
        );

        $this->openPrefix('/chi-siamo');
        $this->assertSentences($sentences);
    }
}
