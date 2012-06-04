<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class ShowCcontattoDocenteTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testShow()
    {
        $this->login('brain');
        $this->open('/v2.php?do=ShowContattoDocente&cod_doc=012179');

        $sentences = array (
                'Prof. Pier Paolo Abbati Marescotti',
                'pier.abbati@unibo.it',
        );

        $this->assertSentences($sentences);
    }

    public function testUpdate()
    {
        $this->login('brain');
        $this->open('/v2.php?do=ShowContattoDocente&cod_doc=012179');
        
        $report = sha1(rand());
        $this->type('name=f35_report', $report);
        $this->clickAndWait('name=f35_submit_report');
        $this->assertSentence($report);
    }
}
