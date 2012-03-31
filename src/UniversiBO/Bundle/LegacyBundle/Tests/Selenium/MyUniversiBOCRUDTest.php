<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class MyUniversiBOCRUDTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testAddNotLogged()
    {
        $this->deleteAllVisibleCookies();
        $this->open('/index.php?do=MyUniversiBOAdd&id_canale=23');
        $this->assertSentences(array('permesso ad utenti non registrati eseguire questa operazione. La sessione potrebbe essere scaduta'));
    }
    
    public function testAdd()
    {
        $this->login('brain');
        $this->open('/index.php?do=MyUniversiBOAdd&id_canale=23');
        $this->assertSentences(array('Aggiungi una nuova pagina al tuo MyUniversiBO'));
        $this->clickAndWait('name=f15_submit');
        $this->assertSentences(array('stata inserita con successo'));
    }
    
    public function testEdit()
    {
    	$this->login('brain');
    	$this->open('/index.php?do=MyUniversiBOEdit&id_canale=23');
    	$this->assertSentences(array('Modifica una pagina del tuo MyUniversiBO'));
    	$this->clickAndWait('name=f19_submit');
    	$this->assertSentences(array('stata modificata con successo'));
    }
    
    public function testRemove()
    {
        $this->login('brain');
        $this->open('/index.php?do=MyUniversiBORemove&id_canale=23');
        $this->assertSentences(array('stata rimossa con successo'));
    }
}