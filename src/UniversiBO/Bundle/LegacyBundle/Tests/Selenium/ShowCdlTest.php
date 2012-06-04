<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class ShowCdlTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $sentences = array (
                'CORSO DI LAUREA DI ECONOMIA AZIENDALE - 0891',
        );

        $this->openCommand('ShowCdl','&id_canale=6172');
        $this->assertSentences($sentences);
    }
}
