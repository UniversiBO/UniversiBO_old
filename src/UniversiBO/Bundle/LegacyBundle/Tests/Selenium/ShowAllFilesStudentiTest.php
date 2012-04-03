<?php
namespace UniversiBO\Bundle\LegacyBundle\Tests\Selenium;

class ShowAllFilesStudentiTest extends UniversiBOSeleniumTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testOrder0()
    {
       $this->testOrder(0, 'nome');
    }
    
    public function testOrder1()
    {
        $this->testOrder(1, 'data di inserimento');
    }
    
    public function testOrder2()
    {
        $this->testOrder(2, 'voto medio');
    }
    
    private function testOrder($order, $orderSentence)
    {
        $this->login('brain');
        $this->openCommand('ShowAllFilesStudenti', '&order='.$order);
        $this->assertSentences(array(
        		'Tutti i Files Studenti presenti su UniversiBO',
        		'ordinati per '.$orderSentence
        ));
    }
}