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
                'CORSO DI LAUREA DI INGEGNERIA INFORMATICA - 0937',
        );

        $this->openPrefix('/cdl/4/');
        $this->assertSentences($sentences);
    }

    public function testNoAcademicYear1()
    {
        $this->openPrefix('/cdl/4/?anno_accademico=2100');
        $this->assertSentence('Not Found');
    }

    public function testNoAcademicYear2()
    {
        $this->openPrefix('/cdl/4/?anno_accademico=200');
        $this->assertSentence('Not Found');
    }
}
