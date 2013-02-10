<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

class ShowPermalinkTest extends UniversiBOSeleniumTestCase
{
    public function testSimple()
    {
        $this->openPrefix('/permalink/10815');
        $this->assertSentences(array(
                'UniversiBO cerca nuovi collaboratori'
        ));
    }
    
    /**
     * @ticket 251
     */
    public function testExpiredNewsShouldNotBeFound()
    {
        $this->openPrefix('/permalink/11134');
        $this->assertSentence('Not Found');
    }
}
