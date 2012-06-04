<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

use UniversiBO\Bundle\LegacyBundle\Tests\TestConstants;

class ShowCcontattiDocentiTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openCommand('ShowContattiDocenti');

        $sentences = array (
                'FERRI MASSIMO',
                'FERRARI EMILIO',
        );

        $this->assertSentences($sentences);
    }
}
