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
        $this->open('/index.php?do=ShowContattoDocente&cod_doc=012179');
        
        $sentences = array (
        		'Prof. Pier Paolo Abbati Marescotti',
        		'pier.abbati@unibo.it',
        );
        
        $this->assertSentences($sentences);
    }
    
    public function testUpdate()
    {
        $this->markTestIncomplete();
    }
}