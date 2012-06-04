<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

use UniversiBO\Bundle\LegacyBundle\Tests\TestConstants;

class NewsShowCanaleTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testNotLogged()
    {
        $this->deleteAllVisibleCookies();
        $this->openCommand('NewsShowCanale', '&id_canale=1&inizio=0&qta=10');
        $this->assertSentence('News');
        
        $this->assertFalse($this->isTextPresent('Scrivi nuova notizia'));
    }
    
    public function testLogged()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
    	$this->openCommand('NewsShowCanale', '&id_canale=1&inizio=0&qta=10');
    	$this->assertSentences(array('News','Scrivi nuova notizia'));
    }
}
