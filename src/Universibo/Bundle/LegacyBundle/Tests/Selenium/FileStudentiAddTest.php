<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class FileStudentiAddTest extends UniversiBOSeleniumTestCase
{
    public function testReadme()
    {
        $this->fileCommon(realpath(dirname(__FILE__).'/../../../../../../README.md'));
        $this->assertTrue($this->isTextPresent('con successo'), 'success message must be present');
    }

    public function testPHP()
    {
        $this->fileCommon(__FILE__);
        $this->assertTrue($this->isTextPresent('severamente vietato'), 'success message must be present');
    }

    private function fileCommon($file)
    {
        $this->login(TestConstants::STUDENT_USERNAME);
        $this->openPrefix('/file/studenti/add/2');
        $this->type('name=f23_file', $file);
        $this->type('name=f23_titolo', 'File title');
        $this->type('name=f23_abstract', 'File abstract');
        $this->clickAndWait('name=f23_submit');
    }
}
