<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class ShowCcontattiDocentiTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $this->login('brain');
        $this->open('/index.php?do=ShowContattiDocenti');

        $sentences = array (
                'FERRI MASSIMO',
                'FERRARI EMILIO',
        );

        $this->assertSentences($sentences);
    }
}
