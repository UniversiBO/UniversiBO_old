<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class ShowContattoDocenteTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testShow()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/docente/012345/contatto/');

        $sentences = array (
                'LAST NAME GIVEN NAME',
                'professor@example.org',
        );

        $this->assertSentences($sentences);
    }

    public function testUpdate()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/docente/012345/contatto/');

        $report = sha1(rand());
        $this->type('name=f35_report', $report);
        $this->clickAndWait('name=f35_submit_report');

        $this->assertSentence($report);
    }
}
