<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

class ShowFacoltaTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $this->logout();
        $sentences = array (
                'FACOLTA\' DI INGEGNERIA - 0021',
        );

        $this->openPrefix('/facolta/3');
        $this->assertSentences($sentences);
    }
}
