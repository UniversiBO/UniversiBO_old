<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class RuoliAdminSearchTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testNotLogged()
    {
        $this->logout();
        $this->openPrefix('/role/admin/search/1415/');

        $this->assertSentences(array(
                'Error',
                'Non hai i diritti per modificare i diritti degli utenti su questa pagina. La sessione potrebbe essere scaduta.'
        ));
    }

    public function testSimple()
    {
        $this->login(TestConstants::ADMIN_USERNAME);

        $this->openPrefix('/role/admin/search/1415/');
        $this->assertSentences(array(
                'Modifica i diritti nella pagina',
                'Area collaboratori'
        ));

        $this->markTestIncomplete('Just stubbed');
    }
}
