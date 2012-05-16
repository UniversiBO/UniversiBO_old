<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class ShowCollaboratoreTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testSimple()
    {
        $sentences = array (
                'Scheda Informativa di Antares',
                'Il mio obiettivo è trovarmi una donna ad Ingegneria. Come un ago in un pagliaio.'
        );

        $this->openCommand('ShowCollaboratore','&id_coll=701');
        $this->assertSentences($sentences);
    }
}
