<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

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

        $this->openCommand('ShowContacts');
        $this->assertSentences($sentences);
    }
}
