<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class FileAddTest extends UniversiBOSeleniumTestCase
{
    public function testReadme()
    {
        $this->fileCommon(realpath(dirname(__FILE__).'/../../../../../../README'));
        $this->assertTrue($this->isTextPresent('con successo'), 'success message must be present');
    }

    public function testPHP()
    {
        $this->fileCommon(__FILE__);
        $this->assertTrue($this->isTextPresent('severamente vietato'), 'success message must be present');
    }

    private function fileCommon($file)
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openCommand('FileAdd', '&id_canale=10294');
        $this->type('name=f12_file', $file);
        $this->type('name=f12_titolo', 'File title');
        $this->type('name=f12_abstract', 'File abstract');
        $this->clickAndWait('name=f12_submit');
    }
}
