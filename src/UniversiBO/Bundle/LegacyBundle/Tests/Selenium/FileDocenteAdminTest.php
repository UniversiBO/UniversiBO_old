<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class FileDocenteAdminTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $this->login('brain');
        $this->open('/index.php?do=FileDocenteAdmin&id_canale=1417');
        $this->assertSentences(array(
                'Gestione file',
                'Seleziona i file da copiare',
                'Seleziona le pagine in cui inserire i file selezionati:'
        ));
        
        $this->markTestIncomplete();
    }
}