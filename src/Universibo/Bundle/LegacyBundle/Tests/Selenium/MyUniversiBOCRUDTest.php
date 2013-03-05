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
        $this->openPrefix('/my/universibo/add/2/');
        $this->assertLoginRequired();
    }

    public function testAddInvalid()
    {
        $this->login(TestConstants::STUDENT_USERNAME);
        $this->openPrefix('/my/universibo/add/655350/');
        $this->assertSentence('Not Found');
    }

    public function testAdd()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/my/universibo/add/2/');
        $this->assertSentence('Aggiungi una nuova pagina al tuo MyUniversiBO');
        $this->clickAndWait('name=f15_submit');
        $this->assertSentence('stata inserita con successo');
    }

    public function testEdit()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/my/universibo/edit/2/');
        $this->assertSentence('Modifica una pagina del tuo MyUniversiBO');
        $this->clickAndWait('name=f19_submit');
        $this->assertSentence('stata modificata con successo');
    }

    public function testRemove()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/my/universibo/remove/2/');
        $this->assertSentence('stata rimossa con successo');
    }
}
