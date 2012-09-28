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
        $this->openPrefix('/docente/012768/contatto/');

        $sentences = array (
                'Prof. Paolo Amadesi',
                'paolo.amadesi@unibo.it',
        );

        $this->assertSentences($sentences);
    }

    public function testUpdate()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/docente/012768/contatto/');

        $report = sha1(rand());
        $this->type('name=f35_report', $report);
        $this->clickAndWait('name=f35_submit_report');

        $this->assertSentence($report);
    }
}
