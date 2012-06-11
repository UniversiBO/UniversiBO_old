<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

class ShowPermalinkTest extends UniversiBOSeleniumTestCase
{
    public function testSimple()
    {
        $this->openCommand('ShowPermalink', '&id_notizia=10815');
        $this->assertSentences(array(
                'UniversiBO cerca nuovi collaboratori'
        ));
    }
}
