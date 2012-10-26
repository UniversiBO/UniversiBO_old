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
        $this->openPrefix('/role/search/1415/');
        $this->assertLoginRequired();
    }

    public function testSearchUsername()
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

    public function testBackLink()
    {
        $this->login(TestConstants::ADMIN_USERNAME);

        $this->openPrefix('/role/admin/search/1415/');
        $this->clickAndWait('link=Torna a Area collaboratori');

        $expected = $this->base .'/canale/1415/';
        $this->assertEquals($expected, strstr($this->getLocation(), $expected));
        $this->assertSentence('Area collaboratori');
    }
}
