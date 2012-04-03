<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class ShowManifestoTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testShow()
    {
        $this->open('/index.php?do=ShowManifesto');
        
        $this->assertSentences(array(
                'aiutare una specie animale che da tempo immemorabile s\'inerpica tutte le mattine per una salitella ai piedi dei colli Bolognesi... si tratta dello studente d\'ingegneria.',
                'farfalla',
                'libera',
                'bruco'
        ));
    }
}