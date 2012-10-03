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
        $this->openPrefix('/myuniversibo/add/23/');
        $this->assertEquals($this->base.'/login', strstr($this->getLocation(), '/app_dev.php/login'));
    }

    public function testAddInvalid()
    {
        $this->login(TestConstants::STUDENT_USERNAME);
        $this->openPrefix('/myuniversibo/add/655350/');
        $this->assertSentence('Not Found');
    }

    public function testAdd()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/myuniversibo/add/23/');
        $this->assertSentence('Aggiungi una nuova pagina al tuo MyUniversiBO');
        $this->clickAndWait('name=f15_submit');
        $this->assertSentence('stata inserita con successo');
    }

    public function testEdit()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/myuniversibo/edit/23/');
        $this->assertSentence('Modifica una pagina del tuo MyUniversiBO');
        $this->clickAndWait('name=f19_submit');
        $this->assertSentence('stata modificata con successo');
    }

    public function testRemove()
    {
        $this->login(TestConstants::ADMIN_USERNAME);
        $this->openPrefix('/myuniversibo/remove/23');
        $this->assertSentence('stata rimossa con successo');
    }
}
