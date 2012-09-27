<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class ShowCcontattiDocentiTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/docenti/contatti');

        $sentences = array (
                'FERRI MASSIMO',
                'FERRARI EMILIO',
        );

        $this->assertSentences($sentences);
    }
}
