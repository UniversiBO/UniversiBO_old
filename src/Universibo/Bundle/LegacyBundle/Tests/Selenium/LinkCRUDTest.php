<?php
namespace Universibo\Bundle\LegacyBundle\Tests\Selenium;

use Universibo\Bundle\LegacyBundle\Tests\TestConstants;

class LinkCRUDTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testLinkAddNotLogged()
    {
        $this->deleteAllVisibleCookies();
        $this->openPrefix('/link/add/1/');

        $this->assertLoginRequired();
    }

    public function testLinkAddLogged()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/link/add/1/');

        $this->assertSentences(array('Aggiungi un nuovo link', 'Indirizzo', 'Etichetta', 'Descrizione'));

        $this->type('name=f29_URI', 'http://www.google.it/');
        $this->type('name=f29_Label', 'Google Italy');
        $this->type('name=f29_Description', 'Google Search Engine in italian language');
        $this->clickAndWait('name=f29_submit');

        $this->assertSentence('inserito con successo.');
    }

    public function testLinkEditNotLogged()
    {
        $this->logout();
        $this->openPrefix('/link/107/edit/1/');
        $this->assertLoginRequired();
    }

    public function testLinkEditLogged()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/link/107/edit/1/');

        $this->type('name=f31_URI', 'http://www.unibo.it/Portale/Ateneo/Strutture/Strutture+di+servizio/80080/AlmaWIFI/default.htm');
        $this->type('name=f31_Label', 'AlmaWIFI - Info');
        $this->type('name=f31_Description', 'Informazioni su AlmaWIFI');
        $this->clickAndWait('name=f31_submit');

        $this->assertSentence('modificato con successo.');
    }

    public function testLinksAdminNotLogged()
    {
        $this->deleteAllVisibleCookies();
        $this->openPrefix('/link/admin/1/');
        $this->assertLoginRequired();
    }

    public function testLinksAdminLogged()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/link/admin/1/');
        $this->assertSentences(array('Gestione Links','fgiardini', 'Google Italy', 'AlmaWIFI'));
        $this->markTestIncomplete('Just stubbed');
    }
}
