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

        $this->openPrefix('/cdl/6172/');
        $this->assertSentences($sentences);
    }

    public function testNoAcademicYear1()
    {
        $this->openPrefix('/cdl/6172/?anno_accademico=2100');
        $this->assertSentence('Not Found');
    }

    public function testNoAcademicYear2()
    {
        $this->openPrefix('/cdl/6172/?anno_accademico=200');
        $this->assertSentence('Not Found');
    }
}
