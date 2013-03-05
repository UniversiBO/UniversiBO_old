<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class RuoliAdminEditTest extends UniversiBOSeleniumTestCase
{
    public function testNotLogged()
    {
        $this->logout();
        $this->openPrefix('/role/edit/1/1/');
        $this->assertLoginRequired();
    }

    public function testEditStudentHomepage()
    {
            $this->login(TestConstants::ADMIN_USERNAME);

        $this->openPrefix('/role/search/1/');
        $this->assertSentences(array(
                'Modifica i diritti nella pagina',
                'Area collaboratori'
        ));

        $this->type('id=f16_username', '%tudent');

        $this->assertSentences(array('Studenti', 'student'));
    }
}
