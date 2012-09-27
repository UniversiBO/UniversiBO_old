<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class FileDocenteAdminTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/file/docente/admin/1417');
        $this->assertSentences(array(
                'Gestione file',
                'Seleziona i file da copiare',
                'Seleziona le pagine in cui inserire i file selezionati:'
        ));

        $this->markTestIncomplete();
    }
}
