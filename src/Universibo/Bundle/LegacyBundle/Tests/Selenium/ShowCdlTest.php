<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

class ShowCdlTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $this->logout();
        $sentences = array (
                'CORSO DI LAUREA DI ECONOMIA AZIENDALE - 0891',
        );

        $this->openCommand('ShowCdl','&id_canale=6172');
        $this->assertSentences($sentences);
    }

    public function testNoAcademicalYear1()
    {
        $this->openCommand('ShowCdl','&id_canale=6172&anno_accademico=2100');
        $this->assertSentence('Not Found');
    }

    public function testNoAcademicalYear2()
    {
        $this->openCommand('ShowCdl','&id_canale=6172&anno_accademico=2000');
        $this->assertSentence('Not Found');
    }
}
