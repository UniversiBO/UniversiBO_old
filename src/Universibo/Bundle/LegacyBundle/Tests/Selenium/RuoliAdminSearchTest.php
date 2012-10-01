<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

class RuoliAdminSearchTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $this->openPrefix('/role/admin/search/1415/');
        $this->assertSentences(array(
                'Modifica i diritti nella pagina',
                'Area collaboratori'
        ));
        $this->markTestIncomplete('Just stubbed');
    }
}
