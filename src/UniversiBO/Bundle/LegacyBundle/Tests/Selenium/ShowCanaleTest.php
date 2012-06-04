<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class ShowCanaleTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $this->open('/v2.php?do=ShowCanale&id_canale=2219');
        $this->assertSentence('Area Laureati');
    }
}
