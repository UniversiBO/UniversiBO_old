<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class MyUniversiBOCRUDTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testAddNotLogged()
    {
        $this->logout();
        $this->openCommand('MyUniversiBOAdd','&id_canale=23');
        $this->assertSentences(array('permesso ad utenti non registrati eseguire questa operazione. La sessione potrebbe essere scaduta'));
    }

    public function testAdd()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openCommand('MyUniversiBOAdd','&id_canale=23');
        $this->assertSentence('Aggiungi una nuova pagina al tuo MyUniversiBO');
        $this->clickAndWait('name=f15_submit');
        $this->assertSentence('stata inserita con successo');
    }

    public function testEdit()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openCommand('MyUniversiBOEdit','&id_canale=23');
        $this->assertSentence('Modifica una pagina del tuo MyUniversiBO');
        $this->clickAndWait('name=f19_submit');
        $this->assertSentence('stata modificata con successo');
    }

    public function testRemove()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openCommand('MyUniversiBORemove','&id_canale=23');
        $this->assertSentence('stata rimossa con successo');
    }
}
