<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

class ShowCollaboratoreTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $sentences = array (
                'Scheda Informativa di moderator',
                'lorem ipsum'
        );

        $this->openPrefix('/chi-siamo/moderator');
        $this->assertSentences($sentences);
    }
}
