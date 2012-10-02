<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class RuoliAdminEditTest extends UniversiBOSeleniumTestCase
{
    public function testNotLogged()
    {
        $this->logout();
        $this->openPrefix('/role/admin/edit/1/81/');

        $this->assertSentences(array(
                'Error',
                'Non hai i diritti per modificare i diritti degli utenti su questa pagina. La sessione potrebbe essere scaduta.'
        ));
    }

    public function testEditBrainHomepage()
    {
            $this->login(TestConstants::ADMIN_USERNAME);

        $this->openPrefix('/role/admin/search/1415/');
        $this->assertSentences(array(
                'Modifica i diritti nella pagina',
                'Area collaboratori'
        ));

        $this->type('id=f16_username', '%giardini');

        $this->assertSentences(array('Studenti', 'fgiardini'));
    }
}
