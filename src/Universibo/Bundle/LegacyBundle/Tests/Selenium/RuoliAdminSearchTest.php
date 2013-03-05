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
        $this->openPrefix('/role/search/2/');
        $this->assertLoginRequired();
    }

    public function testSearchUsername()
    {
        $this->login(TestConstants::ADMIN_USERNAME);

        $this->openPrefix('/role/search/2/');
        $this->assertSentences(array(
                'Modifica i diritti nella pagina',
                'Test channel'
        ));

        $this->type('id=f16_username', '%dmin');
        $this->clickAndWait('id=f16_submit');

        $this->assertSentences(array('Studenti', 'admin'));
    }

    public function testBackLink()
    {
        $this->login(TestConstants::ADMIN_USERNAME);

        $this->openPrefix('/role/search/2/');
        $this->clickAndWait('link=Torna a Test channel');

        $expected = $this->base .'/canale/2/';
        $this->assertEquals($expected, strstr($this->getLocation(), $expected));
        $this->assertSentence('Test channel');
    }
}
