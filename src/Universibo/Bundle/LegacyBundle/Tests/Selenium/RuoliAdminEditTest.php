<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class RuoliAdminEditTest extends UniversiBOSeleniumTestCase
{
    public function testNotLogged()
    {
        $this->logout();
        $this->openPrefix('/role/edit/1/81/');
        $this->assertLoginRequired();
    }

    public function testEditBrainHomepage()
    {
            $this->login(TestConstants::ADMIN_USERNAME);

        $this->openPrefix('/role/search/1415/');
        $this->assertSentences(array(
                'Modifica i diritti nella pagina',
                'Area collaboratori'
        ));

        $this->type('id=f16_username', '%giardini');

        $this->assertSentences(array('Studenti', 'fgiardini'));
    }
}
