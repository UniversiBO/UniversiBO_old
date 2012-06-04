<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class ShowUserTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testNotAllowed()
    {
        $this->login('Dece');
        $this->open('/v2.php?do=ShowUser&id_utente=105');
        
        $this->assertSentence('Non ti e` permesso visualizzare la scheda dell\'utente');
    }
    
    public function testAllowed()
    {
    	$this->login('brain');
    	$this->open('/v2.php?do=ShowUser&id_utente=105');
    
    	$this->assertSentences(array('Utente: fgiardini', 'Livello: Admin'));
    }
}
